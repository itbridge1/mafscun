<footer class="main-footer no-print text-sm">
    {{-- <strong>{{ $_site_setting->copyright_text ?? "Copyrights © ". date('Y') . " All Rights Reserved. " }}</strong> --}}
    <div class="float-right d-none d-sm-inline-block">
        <b>
            <a href="{{ $_site_setting->powered_by_link ?? "https://itbridge.com.np" }}">
                      <p>© {{ date('Y') }} All Rights Reserved {{ $_site_setting->title ?? 'MAFSCUN' }}</p>
            </a>
        </b>
    </div>
   
    
</footer>
