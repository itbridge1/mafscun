@if(isset($_affiliations) && $_affiliations->count() > 0)
    <section class="affiliation-section" aria-labelledby="affiliationTitle">
        <div class="container-xl">
            <div class="affiliation-heading">
                <p class="affiliation-kicker">Our network</p>
                <h2 id="affiliationTitle">Affiliations &amp; partnerships</h2>
                <p>Working together with organizations that strengthen cooperative communities.</p>
            </div>

            <div class="affiliation-carousel-shell">
                <section class="customer-logos" aria-label="Affiliated organizations">
                    @foreach($_affiliations as $affiliation)
                        <div class="affiliation-slide">
                            <a href="{{ $affiliation->link ?: '#' }}" @if($affiliation->link) target="_blank" rel="noopener noreferrer" @endif>
                                <span class="affiliation-logo-frame">
                                    <img src="{{ \App\Facades\ViewHelper::getImagePath('affiliation', $affiliation->logo) }}"
                                         alt="{{ $affiliation->title ?: 'Affiliation partner' }}"
                                         loading="lazy">
                                </span>
                                @if($affiliation->title)
                                    <span class="affiliation-name">{{ $affiliation->title }}</span>
                                @endif
                            </a>
                        </div>
                    @endforeach
                </section>
            </div>
        </div>
    </section>
@endif
