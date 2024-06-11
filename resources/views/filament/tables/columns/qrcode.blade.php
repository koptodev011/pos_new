<div class="py-2">
    {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->backgroundColor(255,255,255)->generate($getRecord()->share_url) !!}
</div>