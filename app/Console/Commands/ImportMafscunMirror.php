<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\AboutUs;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Models\Hrm;
use App\Models\News;
use App\Models\Notice;
use App\Models\Resource;
use App\Models\ResourceType;
use App\Models\SiteSetting;
use App\Models\Slider;
use App\Models\StatisticsDetail;
use App\Models\Team;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Throwable;

class ImportMafscunMirror extends Command
{
    protected $signature = 'mafscun:import-mirror {--source= : Mirror root (defaults to webscrap/mirror/mafscun.coop.np)} {--dry-run : Report articles without writing database records}';
    protected $description = 'Import archived MAFSCUN posts and media into the website news archive';

    public function handle(): int
    {
        $root = realpath($this->option('source') ?: base_path('webscrap/mirror/mafscun.coop.np'));
        if (!$root || !is_file($root . DIRECTORY_SEPARATOR . 'index.html')) {
            $this->error('Mirror root not found or missing index.html.');
            return self::FAILURE;
        }

        $pages = $this->recursiveIndexFiles($root);
        $articles = [];
        foreach ($pages as $page) {
            $parsed = $this->parseArticle($root, $page);
            if ($parsed) $articles[] = $parsed;
        }
        usort($articles, fn ($a, $b) => $a['date'] <=> $b['date']);
        $this->info('Found ' . count($articles) . ' article pages.');

        $aboutPages = $this->parseStaticPages($root);
        $pdfs = $this->recursiveFiles($root . '/wp-content/uploads', 'pdf');
        $this->info('Found ' . count($aboutPages) . ' standalone pages and ' . count($pdfs) . ' PDF resources.');

        if ($this->option('dry-run')) {
            foreach (array_slice($articles, 0, 12) as $article) {
                $this->line($article['date']->format('Y-m-d') . '  ' . $article['title']);
            }
            if (count($articles) > 12) $this->line('... and ' . (count($articles) - 12) . ' more.');
            $this->line('Standalone pages: ' . implode(', ', array_column($aboutPages, 'title')));
            return self::SUCCESS;
        }

        $categories = [
            'news' => Category::firstOrCreate(['slug' => 'mafscun-news'], ['title' => 'समाचार | News', 'sub_title' => 'MAFSCUN archive', 'rank' => 9997, 'status' => 1]),
        ];
        News::where('slug', 'like', 'mafscun-%')->update(['category_id' => $categories['news']->id]);

        $counts = ['news' => 0, 'notice' => 0, 'event' => 0, 'about' => 0, 'resources' => 0, 'sliders' => 0, 'galleries' => 0, 'hrmGroups' => 0, 'teamProfiles' => 0, 'leadershipMessages' => 0, 'skipped' => 0];
        foreach ($articles as $article) {
            $kind = $this->classify($article['title']);
            if ($kind === 'about') {
                $stored = $this->storeAboutPage($article);
                $counts['about'] += $stored ? 1 : 0;
                if ($stored) News::where('slug', $article['slug'])->where('slug', 'like', 'mafscun-%')->delete();
                continue;
            }
            $existingNews = News::where('slug', $article['slug'])->first();
            if (($kind === 'notice' && Notice::where('slug', $article['slug'])->exists()) || ($kind === 'event' && Event::where('slug', $article['slug'])->exists()) || ($kind === 'news' && $existingNews)) {
                $counts['skipped']++;
                $counts['galleries'] += $this->storeGallery($article) ? 1 : 0;
                continue;
            }
            if ($existingNews && !str_starts_with($existingNews->slug, 'mafscun-')) {
                $counts['skipped']++;
                continue;
            }
            $description = $existingNews->description ?? $article['description'];
            $photo = $existingNews->photo ?? $article['photo'];
            if ($kind === 'notice') {
                $notice = new Notice(['title' => $article['title'], 'sub_title' => 'सूचना | Notice', 'slug' => $article['slug'], 'photo' => $this->copyCoverPhoto($photo, 'notice'), 'description' => $description, 'status' => 1, 'rank' => 9999]);
                $notice->created_at = $existingNews->created_at ?? $article['date'];
                $notice->updated_at = $existingNews->updated_at ?? $article['date'];
                $notice->save();
            } elseif ($kind === 'event') {
                $event = new Event(['title' => $article['title'], 'slug' => $article['slug'], 'start_date' => $article['date']->toDateString(), 'total_seats' => 0, 'event_price' => 0, 'price_type' => 'per_person', 'photo' => $this->copyCoverPhoto($photo, 'event'), 'short_description' => Str::limit(strip_tags($description), 220), 'description' => $description, 'status' => 1]);
                $event->created_at = $existingNews->created_at ?? $article['date'];
                $event->updated_at = $existingNews->updated_at ?? $article['date'];
                $event->save();
            } else {
                $news = new News(['category_id' => $categories['news']->id, 'title' => $article['title'], 'slug' => $article['slug'], 'photo' => $photo, 'view_count' => 0, 'description' => $description, 'author' => 'MAFSCUN', 'status' => 1]);
                $news->created_at = $article['date'];
                $news->updated_at = $article['date'];
                $news->save();
            }
            if ($existingNews) $existingNews->delete();
            $counts[$kind]++;
            $counts['galleries'] += $this->storeGallery($article) ? 1 : 0;
        }

        foreach ($aboutPages as $page) $counts['about'] += $this->storeAboutPage($page) ? 1 : 0;
        $counts['resources'] = $this->importPdfs($root, $pdfs) + $this->importPageResources($aboutPages);
        $counts['sliders'] = $this->importSliders($root, $articles);
        $hrmCounts = $this->importHumanResources($root);
        $counts['hrmGroups'] = $hrmCounts['groups'];
        $counts['teamProfiles'] = $hrmCounts['teams'];
        $counts['leadershipMessages'] = $this->importLeadershipMessages($root);
        $this->importBranding($root);
        $counts['savingsStats'] = $this->importSavingsDetails();
        $this->sanitizeImportedContent();
        $this->info('Imported: ' . json_encode($counts, JSON_UNESCAPED_UNICODE));
        return self::SUCCESS;
    }

    private function recursiveIndexFiles(string $root): array
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS));
        foreach ($iterator as $file) {
            if ($file->isFile() && strtolower($file->getFilename()) === 'index.html') $files[] = $file->getPathname();
        }
        return $files;
    }

    private function recursiveFiles(string $root, string $extension): array
    {
        if (!is_dir($root)) return [];
        $files = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS));
        foreach ($iterator as $file) {
            if ($file->isFile() && strtolower($file->getExtension()) === strtolower($extension)) $files[] = $file->getPathname();
        }
        return $files;
    }

    private function classify(string $title): string
    {
        if (preg_match('/उद्देश्य|ध्येय|परिकल्पना|संक्षिप्त परिचय|mission|vision|objectives|about us/i', $title)) return 'about';
        if (preg_match('/सूचना|बोलपत्र|कोटेशन|दरभाउ|निवेदन|आवेदन|शिलबन्दी|quotation|invitation for bid|notice|सूचित गराउने/i', $title)) return 'notice';
        if (preg_match('/कार्यक्रम|सभा|भेला|दिवस|गोष्ठी|तालिम|प्रशिक्षण|अन्तरक्रिया|समारोह|सम्मेलन|अधिवेशन|कार्यशाला|event|training/i', $title)) return 'event';
        return 'news';
    }

    private function parseStaticPages(string $root): array
    {
        $pages = [];
        foreach (['training.html', 'stationery.html', 'mafscun-6.html'] as $name) {
            $file = $root . DIRECTORY_SEPARATOR . $name;
            if (!is_file($file)) continue;
            $html = file_get_contents($file);
            $dom = new DOMDocument('1.0', 'UTF-8');
            $previous = libxml_use_internal_errors(true);
            $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_NONET | LIBXML_NOWARNING | LIBXML_NOERROR);
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
            $xpath = new DOMXPath($dom);
            $heading = $xpath->query('//h1')->item(0);
            $body = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " td-page-content ") or contains(concat(" ", normalize-space(@class), " "), " td-post-content ")]')->item(0);
            if (!$heading || !$body) continue;
            $content = '';
            foreach ($body->childNodes as $child) $content .= $dom->saveHTML($child);
            $title = trim(preg_replace('/\s+/u', ' ', $heading->textContent));
            $slug = 'mafscun-page-' . pathinfo($name, PATHINFO_FILENAME);
            $pages[] = ['title' => Str::limit($title, 250, ''), 'slug' => $slug, 'description' => $this->cleanContent($this->rewriteMediaUrls($content)), 'photo' => null, 'date' => Carbon::create(2020, 4, 19)];
        }
        return $pages;
    }

    private function importHumanResources(string $root): array
    {
        $definitions = [
            'कर्मचारी' => ['slug' => 'mafscun-hrm-staff', 'rank' => 3, 'description' => 'संघका कर्मचारी तथा व्यवस्थापन टोली | Staff and management team'],
            'सञ्चालक-समिति' => ['slug' => 'mafscun-hrm-board', 'title' => 'सञ्चालक समिति', 'rank' => 1, 'description' => 'संघको सञ्चालक समिति | Board of Directors'],
            'लेखा-तथा-सुपरीवेक्षण-समि' => ['slug' => 'mafscun-hrm-audit', 'title' => 'लेखा तथा सुपरिवेक्षण समिति', 'rank' => 2, 'description' => 'संघको लेखा तथा सुपरिवेक्षण समिति | Audit and Supervision Committee'],
        ];
        $counts = ['groups' => 0, 'teams' => 0];
        $staffGroup = null;
        foreach ($definitions as $directory => $definition) {
            $group = Hrm::firstOrNew(['slug' => $definition['slug']]);
            $isNewGroup = !$group->exists;
            $group->title = $definition['title'] ?? $directory;
            $group->description = $definition['description'];
            $group->rank = $definition['rank'];
            $group->status = 1;
            $group->save();
            if ($isNewGroup) $counts['groups']++;
            if ($directory === 'कर्मचारी') $staffGroup = $group;

            $categoryRoot = $root . '/category/मानव-संसाधन/' . $directory;
            $listings = array_values(array_filter($this->recursiveIndexFiles($categoryRoot), fn ($path) => !str_contains(str_replace('\\', '/', $path), '/feed/')));
            sort($listings, SORT_STRING);
            $seen = [];
            $rank = 1;
            foreach ($listings as $listing) {
                $dom = new DOMDocument('1.0', 'UTF-8');
                $previous = libxml_use_internal_errors(true);
                $dom->loadHTML('<?xml encoding="utf-8" ?>' . file_get_contents($listing), LIBXML_NONET | LIBXML_NOWARNING | LIBXML_NOERROR);
                libxml_clear_errors();
                libxml_use_internal_errors($previous);
                $xpath = new DOMXPath($dom);
                foreach ($xpath->query('//h3[contains(concat(" ", normalize-space(@class), " "), " entry-title ")]/a[@href]') as $link) {
                    $href = html_entity_decode($link->getAttribute('href'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $path = parse_url($href, PHP_URL_PATH);
                    if (!$path || preg_match('~^(?:https?:)?//~i', $href)) continue;
                    $target = str_starts_with($path, '/') ? realpath($root . $path) : realpath(dirname($listing) . '/' . rawurldecode($path));
                    if (!$target || isset($seen[$target])) continue;
                    $seen[$target] = true;
                    $article = $this->parseArticle($root, $target);
                    if (!$article) continue;
                    $label = trim(preg_replace('/\s+/u', ' ', $link->textContent));
                    [$name, $designation] = $this->splitPersonLabel($label);
                    if ($this->saveTeamProfile($group, $article, $name, $designation, $rank++)) $counts['teams']++;
                    News::where('slug', $article['slug'])->where('slug', 'like', 'mafscun-%')->delete();
                }
            }
            $this->deduplicateTeamProfiles($group);
        }

        // The March 2020 staff page has the manager's portrait and direct phone number.
        $managerPage = $root . '/2020/03/सुमित्रा-लामा-व्यवस्थाप/index.html';
        $manager = $staffGroup && is_file($managerPage) ? $this->parseArticle($root, $managerPage) : null;
        if ($manager && $staffGroup) {
            $this->saveTeamProfile($staffGroup, $manager, $manager['title'], 'व्यवस्थापक', 1, true);
            News::where('slug', $manager['slug'])->where('slug', 'like', 'mafscun-%')->delete();
        }
        $boardGroup = Hrm::where('slug', 'mafscun-hrm-board')->first();
        $auditGroup = Hrm::where('slug', 'mafscun-hrm-audit')->first();
        foreach ([
            [$boardGroup, $root . '/2024/03/श्री-यशोदा-पराजुली-संचाल/index.html', 'श्री यशोदा पराजुली', 'संचालक सदस्य', 1],
            [$boardGroup, $root . '/2024/03/श्री-जानकी-चौधरी-संचालक-स/index.html', 'श्री जानकी चौधरी', 'संचालक सदस्य', 2],
            [$auditGroup, $root . '/2020/04/mitharam-dahal/index.html', 'श्री मिठाराम दाहाल', 'लेखा संयोजक', 1],
        ] as [$group, $path, $name, $designation, $rank]) {
            if (!$group || !is_file($path)) continue;
            $profile = $this->parseArticle($root, $path);
            if (!$profile) continue;
            $this->saveTeamProfile($group, $profile, $name, $designation, $rank, true);
            News::where('slug', $profile['slug'])->where('slug', 'like', 'mafscun-%')->delete();
        }
        foreach ([$staffGroup, $boardGroup, $auditGroup] as $group) if ($group) $this->deduplicateTeamProfiles($group);
        return $counts;
    }

    private function splitPersonLabel(string $label): array
    {
        $label = preg_replace('/^[\s,،।]+|[\s,،।]+$/u', '', preg_replace('/\s+/u', ' ', $label));
        if (preg_match('/^(.+?)\s*[,،]\s*(.+)$/u', $label, $parts)) {
            $name = preg_replace('/^[\s,،।]+|[\s,،।]+$/u', '', $parts[1]);
            $designation = preg_replace('/^[\s,،।]+|[\s,،।]+$/u', '', $parts[2]);
            return [$name, $designation];
        }
        $roles = ['सञ्चालक सदस्य', 'संचालक सदस्य', 'कार्यक्रम संयोजक', 'कार्यालय सहयोगी', 'बजार प्रतिनिधि', 'लेखा संयोजक', 'लेखा सदस्य', 'ऋण अधिकृत', 'सह–सचिव', 'अध्यक्ष', 'उपाध्यक्ष', 'सचिव', 'कोषाध्यक्ष', 'सदस्य', 'ब्यवस्थापक', 'व्यवस्थापक', 'लेखापाल', 'क्यासियर'];
        foreach ($roles as $role) {
            if (preg_match('/[,،]?\s*' . preg_quote($role, '/') . '\s*[,،]?$/u', $label)) {
                $name = preg_replace('/^[\s,،।]+|[\s,،।]+$/u', '', preg_replace('/[,،]?\s*' . preg_quote($role, '/') . '\s*[,،]?$/u', '', $label));
                return [$name ?: $label, $role];
            }
        }
        return [$label, ''];
    }

    private function saveTeamProfile(Hrm $group, array $article, string $name, string $designation, int $rank, bool $preferSource = false): bool
    {
        [$nameFromTitle] = $this->splitPersonLabel($name);
        $name = $nameFromTitle;
        $normalized = fn ($value) => trim(preg_replace('/^(श्री|श्रीमती|श्रीमान्)\s*/u', '', preg_replace('/\s+/u', ' ', $value)));
        $teamSlug = 'mafscun-team-' . substr(sha1($article['slug'] . $group->id), 0, 16);
        $team = Team::where('hrm_id', $group->id)->where('slug', $teamSlug)->first();
        if (!$team) $team = Team::where('hrm_id', $group->id)->get()->first(function ($item) use ($normalized, $name) {
            $old = $normalized($item->name);
            $new = $normalized($name);
            return $old !== '' && ($old === $new || str_starts_with($old, $new) || str_starts_with($new, $old));
        });
        if (!$team) $team = Team::where('hrm_id', $group->id)->where('name', '')->where('slug', 'like', 'mafscun-team-%')->first();
        if (!$team && $preferSource) $team = Team::where('hrm_id', $group->id)->whereIn('designation', ['व्यवस्थापक', 'ब्यवस्थापक'])->first();
        $created = !$team;
        if (!$team) $team = new Team();
        $team->hrm_id = $group->id;
        $team->name = $name;
        $team->slug = $team->slug ?: $teamSlug;
        if ($designation !== '') $team->designation = $designation;
        $team->photo = $this->copyCoverPhoto($article['photo'], 'team') ?: $team->photo;
        $team->description = $article['description'] ?: $team->description;
        $team->status = 1;
        $team->rank = $created ? $rank : $team->rank;
        if (preg_match('/([०-९0-9]{7,})/u', strip_tags($article['description']), $phone)) $team->phone = $phone[1];
        $team->save();
        if (!$group->photo && $team->photo) $group->update(['photo' => $team->photo]);
        return $created;
    }

    private function deduplicateTeamProfiles(Hrm $group): void
    {
        $seen = [];
        foreach (Team::where('hrm_id', $group->id)->where('slug', 'like', 'mafscun-team-%')->orderBy('id')->get() as $team) {
            $name = trim(preg_replace('/\s+/u', ' ', $team->name ?? ''));
            if ($name === '') {
                $team->delete();
                continue;
            }
            $key = preg_replace('/^(श्री|श्रीमती|श्रीमान्)\s*/u', '', $name);
            if (isset($seen[$key])) {
                $photo = public_path('images/team/' . $team->photo);
                if ($team->photo && str_starts_with($team->photo, 'mafscun-') && is_file($photo)) unlink($photo);
                $team->delete();
                continue;
            }
            $seen[$key] = true;
        }
    }

    private function importLeadershipMessages(string $root): int
    {
        $imported = 0;
        $file = $root . '/2020/03/बिहीबारदेखि-सुरु-हुने-भन/index.html';
        if (is_file($file) && ($article = $this->parseArticle($root, $file))) {
            $message = AboutUs::firstOrNew(['slug' => 'mafscun-chairperson-message-2020-03']);
            $wasNew = !$message->exists;
            $message->title = 'अध्यक्षको सन्देश';
            $message->sub_title = 'कल्पना श्रेष्ठ | अध्यक्ष | Chairperson';
            $message->photo = $this->copyCoverPhoto($article['photo'], 'aboutUs');
            $message->description = $article['description'];
            $message->status = 1;
            $message->rank = 1;
            $message->save();
            if ($wasNew) {
                $message->created_at = $article['date'];
                $message->updated_at = $article['date'];
                $message->save();
                $imported++;
            }
            News::where('slug', $article['slug'])->where('slug', 'like', 'mafscun-%')->delete();
        }

        $managerFile = $root . '/2020/03/सुमित्रा-लामा-व्यवस्थाप/index.html';
        if (is_file($managerFile) && ($managerArticle = $this->parseArticle($root, $managerFile))) {
            $message = AboutUs::firstOrNew(['slug' => 'mafscun-manager-message-2020-03']);
            $wasNew = !$message->exists;
            $message->title = 'व्यवस्थापकको सन्देश';
            $message->sub_title = 'सुमित्रा कुमारी वाईबा | व्यवस्थापक | Manager';
            $message->photo = $this->copyCoverPhoto($managerArticle['photo'], 'aboutUs');
            $message->description = $managerArticle['description'];
            $message->status = 1;
            $message->rank = 2;
            $message->save();
            if ($wasNew) {
                $message->created_at = $managerArticle['date'];
                $message->updated_at = $managerArticle['date'];
                $message->save();
                $imported++;
            }
        }
        return $imported;
    }

    private function storeAboutPage(array $page): bool
    {
        if (AboutUs::where('slug', $page['slug'])->exists()) return false;
        $row = new AboutUs([
            'title' => $page['title'],
            'slug' => $page['slug'],
            'sub_title' => 'मकवानपुर जिल्ला बचत तथा ऋण सहकारी संघ | MAFSCUN',
            'photo' => $this->copyCoverPhoto($page['photo'], 'aboutUs'),
            'description' => $page['description'],
            'status' => 1,
            'rank' => 9000,
        ]);
        $row->created_at = $page['date'];
        $row->updated_at = $page['date'];
        $row->save();
        return true;
    }

    private function copyCoverPhoto(?string $photo, string $folder): ?string
    {
        if (!$photo) return null;
        $source = public_path('images/news/' . $photo);
        if (!is_file($source)) return null;
        $name = 'mafscun-' . substr(sha1($folder . $photo), 0, 12) . '-' . basename($source);
        $directory = public_path('images/' . $folder);
        if (!is_dir($directory)) mkdir($directory, 0775, true);
        $destination = $directory . DIRECTORY_SEPARATOR . $name;
        if (!is_file($destination)) copy($source, $destination);
        return $name;
    }

    private function storeGallery(array $article): bool
    {
        if (count($article['images']) < 2) return false;
        $slug = $article['slug'] . '-gallery';
        if (Gallery::where('slug', $slug)->exists()) return false;
        $galleryPhotos = [];
        foreach ($article['images'] as $index => $image) {
            $source = public_path('images/news/' . $image['photo']);
            if (!is_file($source)) continue;
            $name = 'mafscun-' . substr(sha1($slug . $index), 0, 12) . '-' . basename($source);
            $directory = public_path('images/gallery');
            if (!is_dir($directory)) mkdir($directory, 0775, true);
            if (copy($source, $directory . DIRECTORY_SEPARATOR . $name)) $galleryPhotos[] = ['name' => $name, 'alt' => $image['alt']];
        }
        if (count($galleryPhotos) < 2) return false;
        $gallery = Gallery::create(['title' => $article['title'], 'sub_title' => 'समाचार तथा कार्यक्रमका तस्बिर | News & event photos', 'slug' => $slug, 'status' => 1, 'description' => $article['description'], 'cover_photo' => $galleryPhotos[0]['name'], 'rank' => 9000]);
        foreach ($galleryPhotos as $rank => $photo) GalleryImage::create(['gallery_id' => $gallery->id, 'photo' => $photo['name'], 'rank' => $rank + 1, 'alt_text' => $photo['alt'] ?: $article['title'], 'caption' => $article['title'], 'status' => 1]);
        return true;
    }

    private function importPdfs(string $root, array $pdfs): int
    {
        if (!$pdfs) return 0;
        $type = ResourceType::firstOrCreate(['slug' => 'mafscun-documents'], ['title' => 'प्रकाशन तथा फाराम | Publications & Forms', 'description' => 'MAFSCUN publications and forms', 'rank' => 9000, 'status' => 1]);
        $created = 0;
        foreach ($pdfs as $source) {
            $relative = str_replace('\\', '/', substr($source, strlen($root . '/wp-content/uploads/')));
            $stored = 'mafscun/wp-content/uploads/' . $relative;
            $destination = public_path('images/resource/' . $stored);
            if (!is_dir(dirname($destination))) mkdir(dirname($destination), 0775, true);
            if (!is_file($destination)) copy($source, $destination);
            if (Resource::where('slug', 'mafscun-file-' . substr(sha1($relative), 0, 12))->exists()) continue;
            $title = pathinfo(basename($source), PATHINFO_FILENAME);
            $title = preg_replace('/[-_]+/u', ' ', $title);
            Resource::create(['resource_type_id' => $type->id, 'title' => Str::limit($title, 150, ''), 'sub_title' => 'आधिकारिक प्रकाशन | Official publication', 'slug' => 'mafscun-file-' . substr(sha1($relative), 0, 12), 'type' => 'file', 'file' => $stored, 'description' => 'मकवानपुर जिल्ला बचत तथा ऋण सहकारी संघबाट प्रकाशित आधिकारिक कागजात।', 'status' => 1, 'rank' => 9000]);
            $created++;
        }
        return $created;
    }

    private function importPageResources(array $pages): int
    {
        $type = ResourceType::firstOrCreate(['slug' => 'mafscun-documents'], ['title' => 'प्रकाशन तथा फाराम | Publications & Forms', 'description' => 'MAFSCUN publications and forms', 'rank' => 9000, 'status' => 1]);
        $created = 0;
        foreach ($pages as $page) {
            if (!in_array($page['slug'], ['mafscun-page-training', 'mafscun-page-stationery'], true)) continue;
            $slug = $page['slug'] . '-resource';
            if (Resource::where('slug', $slug)->exists()) continue;
            Resource::create(['resource_type_id' => $type->id, 'title' => $page['title'], 'sub_title' => 'सेवा तथा जानकारी | Information', 'slug' => $slug, 'type' => 'link', 'file' => '/about-us/' . $page['slug'], 'description' => 'विस्तृत जानकारीका लागि हेर्नुहोस्। | View the full information.', 'status' => 1, 'rank' => 9000]);
            $created++;
        }
        return $created;
    }

    private function importSliders(string $root, array $articles): int
    {
        $hasImportedSlides = Slider::where('photo', 'like', 'mafscun-%')->exists();
        if ($hasImportedSlides) {
            $rows = Slider::where('photo', 'like', 'mafscun-%')->orderBy('rank')->orderBy('id')->get();
            foreach ($rows->slice(5) as $duplicate) {
                $file = public_path('images/slider/' . $duplicate->photo);
                if (is_file($file)) unlink($file);
                $duplicate->delete();
            }
            foreach ($rows->take(5)->values() as $index => $row) {
                if ($row->rank !== $index + 1) $row->update(['rank' => $index + 1]);
            }
            return 0;
        }
        Slider::whereNotNull('rank')->increment('rank', 5);
        $candidates = array_reverse(array_filter($articles, fn ($article) => $article['photo'] && $this->classify($article['title']) !== 'about'));
        $added = 0;
        $rank = 1;
        foreach ($candidates as $article) {
            if ($added >= 5) break;
            $source = public_path('images/news/' . $article['photo']);
            if (!is_file($source)) continue;
            $name = 'mafscun-' . substr(sha1($article['slug']), 0, 12) . '-' . basename($source);
            $directory = public_path('images/slider');
            if (!is_dir($directory)) mkdir($directory, 0775, true);
            $destination = $directory . DIRECTORY_SEPARATOR . $name;
            if (!is_file($destination)) copy($source, $destination);
            if (Slider::where('photo', $name)->exists()) continue;
            Slider::create(['alt_text' => $article['title'], 'caption' => $article['title'] . ' | MAFSCUN', 'photo' => $name, 'rank' => $rank++, 'status' => 1]);
            $added++;
        }
        return $added;
    }

    private function importBranding(string $root): void
    {
        $sourceDir = $root . '/wp-content/uploads/2020/04';
        $logoSource = $sourceDir . '/mafscun.png';
        $faviconSource = $root . '/wp-content/uploads/2020/03/cropped-logo-32x32.png';
        $targetDir = public_path('images/siteSetting');
        if (!is_dir($targetDir)) mkdir($targetDir, 0775, true);
        if (is_file($logoSource)) copy($logoSource, $targetDir . '/mafscun-logo.png');
        if (is_file($faviconSource)) copy($faviconSource, $targetDir . '/mafscun-favicon.png');
        $settings = SiteSetting::first();
        if ($settings && is_file($logoSource) && is_file($faviconSource)) {
            $settings->title = 'जिल्ला बचत तथा ऋण सहकारी संघ लि., मकवानपुर';
            $settings->sub_title = 'Makawanpur District Federation of Saving & Credit Co-operative Union Ltd.';
            $settings->phone = '057-521172';
            $settings->email = 'info@mafscun.coop.np';
            $settings->location = 'हेटौंडा उपमहानगरपालिका–२, शान्तिपथ, मकवानपुर, बागमती प्रदेश, नेपाल';
            $settings->site_logo = 'mafscun-logo.png';
            $settings->site_favicon = 'mafscun-favicon.png';
            $settings->company_logo = 'mafscun-logo.png';
            $settings->copyright_text = 'सञ्चालन तथा प्रकाशन अधिकार जिल्ला बचत तथा ऋण सहकारी संघ लि., मकवानपुरमा सुरक्षित रहनेछ।';
            $intro = AboutUs::where('slug', 'like', 'mafscun-%')->where('title', 'like', '%संक्षिप्त परिचय%')->first();
            $mission = AboutUs::where('slug', 'like', 'mafscun-%')->where('title', 'like', '%ध्येय%')->first();
            $chairpersonMessage = AboutUs::where('slug', 'mafscun-chairperson-message-2020-03')->first();
            $managerMessage = AboutUs::where('slug', 'mafscun-manager-message-2020-03')->first();
            $objectives = AboutUs::where('slug', 'like', 'mafscun-%')->where('title', 'like', '%उद्देश्य%')->first();
            if ($intro) $settings->about_1 = $intro->id;
            if ($mission) {
                $mission->title = 'ध्येय (Mission)';
                $mission->sub_title = 'संघको ध्येय | Our Mission';
                $mission->save();
            }
            if ($chairpersonMessage) $settings->about_2 = $chairpersonMessage->id;
            if ($managerMessage) $settings->about_3 = $managerMessage->id;
            if ($objectives) $settings->about_4 = $objectives->id;
            $settings->save();
        }
    }

    private function importSavingsDetails(): int
    {
        $settings = SiteSetting::first();
        if (!$settings) return 0;
        $settings->statistics_text = 'बचतको प्रकारहरू | Savings Types';
        $settings->save();
        $products = [
            ['नियमित बचत', 'न्यूनतम मौज्दात: रु. १,०००–२०,००० · ब्याजदर: १–३.०%'],
            ['भवन बचत', 'न्यूनतम मौज्दात: रु. ५०,००० देखि · हालको ब्याजदर: ०.२५%'],
            ['ऐच्छिक बचत', 'न्यूनतम मौज्दात: रु. २५,००० · ब्याजदर: ०.२५%'],
            ['सुनौलो बचत', 'न्यूनतम मौज्दात: रु. २ लाख · ब्याजदर: ०.२५% · पूँजी वृद्धि स्किम: ०.५०%'],
            ['तरलता बचत', 'न्यूनतम मौज्दात: रु. ५० हजार · ब्याजदर: १.०%'],
            ['दैनिक बचत', 'न्यूनतम मौज्दात: रु. ५,००० · ब्याजदर: ०.१०%'],
            ['आग्रह बचत', 'न्यूनतम मौज्दात: रु. २ लाख · ब्याजदर: ०.१०%'],
            ['आवधिक बचत', '३ महिना: ०.७५% · ६ महिना: १.५% · १ वर्ष: २.०%'],
        ];
        foreach ($products as $index => [$name, $value]) {
            StatisticsDetail::updateOrCreate(
                ['site_setting_id' => $settings->id, 'name' => $name],
                ['value' => $value, 'rank' => $index + 1]
            );
        }
        return count($products);
    }

    private function cleanContent(string $html): string
    {
        $html = preg_replace('~<script\b[^>]*>.*?</script\s*>~is', '', $html);
        $html = preg_replace('~<style\b[^>]*>.*?</style\s*>~is', '', $html);
        $html = preg_replace('~<iframe\b[^>]*>.*?</iframe\s*>~is', '', $html);
        $html = preg_replace('~<(?:div|aside)\b[^>]*class=["\'][^"\']*(?:fb-comments|fb_iframe_widget|fb-background-color|M495716ScriptRoot|td-a-rec)[^"\']*["\'][^>]*>.*?</(?:div|aside)\s*>~is', '', $html);
        return preg_replace('/\s+on[a-z]+\s*=\s*(["\']).*?\1/is', '', $html);
    }

    private function sanitizeImportedContent(): void
    {
        foreach ([News::class, Notice::class, Event::class, AboutUs::class, Team::class, Gallery::class] as $model) {
            $model::where('slug', 'like', 'mafscun-%')->whereNotNull('description')->chunkById(100, function ($rows) {
                foreach ($rows as $row) {
                    $clean = $this->cleanContent($row->description);
                    if ($clean !== $row->description) $row->update(['description' => $clean]);
                }
            });
        }
    }

    private function parseArticle(string $root, string $file): ?array
    {
        $html = @file_get_contents($file);
        if (!$html) return null;
        $dom = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_NONET | LIBXML_NOWARNING | LIBXML_NOERROR);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);
        $xpath = new DOMXPath($dom);
        $titleNode = $xpath->query('//h1[contains(concat(" ", normalize-space(@class), " "), " entry-title ")]')->item(0);
        $bodyNode = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " td-post-content ")]')->item(0);
        if (!$titleNode || !$bodyNode) return null;
        $title = trim(preg_replace('/\s+/u', ' ', $titleNode->textContent));
        if ($title === '') return null;

        $timeNode = $xpath->query('//time[@datetime]')->item(0);
        try {
            $date = $timeNode ? Carbon::parse($timeNode->getAttribute('datetime')) : Carbon::createFromTimestamp(filemtime($file));
        } catch (Throwable $e) {
            $date = Carbon::createFromTimestamp(filemtime($file));
        }

        $content = '';
        foreach ($bodyNode->childNodes as $child) $content .= $dom->saveHTML($child);
        $content = $this->cleanContent($this->rewriteMediaUrls($content));

        $photo = null;
        $images = [];
        foreach ($bodyNode->getElementsByTagName('img') as $image) {
            if (!$image instanceof DOMElement) continue;
            $src = $image->getAttribute('src');
            if (preg_match('~(?:https?://[^/]+)?(?:\.\./|/)*wp-content/uploads/(.+)$~u', html_entity_decode($src), $match)) {
                $relativeImage = ltrim(rawurldecode($match[1]), '/');
                $imageFile = $root . '/wp-content/uploads/' . $relativeImage;
                if (is_file($imageFile)) {
                    $imageData = ['photo' => 'mafscun/wp-content/uploads/' . $relativeImage, 'alt' => trim($image->getAttribute('alt'))];
                    if (!in_array($imageData['photo'], array_column($images, 'photo'), true)) $images[] = $imageData;
                    if (!$photo) $photo = $imageData['photo'];
                }
            }
        }
        if (!$photo && preg_match('~(\d{4}/\d{2}/[^/]+\.(?:jpe?g|png))~i', $content, $match)) {
            $photo = 'mafscun/wp-content/uploads/' . $match[1];
        }

        $relative = str_replace('\\', '/', substr($file, strlen($root) + 1));
        $slug = 'mafscun-' . Str::slug($title) . '-' . substr(sha1($relative), 0, 8);
        return ['title' => Str::limit($title, 250, ''), 'slug' => Str::limit($slug, 240, ''), 'description' => $content, 'date' => $date, 'photo' => $photo, 'images' => $images];
    }

    private function rewriteMediaUrls(string $html): string
    {
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return preg_replace('~(?:https?://mafscun\.coop\.np)?(?:\.\./|/)*wp-content/uploads/~u', '/images/news/mafscun/wp-content/uploads/', $html);
    }
}
