<div class="col-6">
<a style="display: block; margin-bottom: 10px; height: 60px;" href="/profile/{{ $profile->id }}">
<div class="row">
    <div class="col-4" style="padding-right: 0;">
        <div>
            <img style="max-width: 100%;  -webkit-border-radius: 50%;-moz-border-radius: 50%;border-radius: 50%;" src="{{ $profile->photo }}" alt="{{ $profile->first_name }} {{ $profile->last_name }}">
        </div>
    </div>
    <div class="col-8">
        <p style="margin: 0;display: table-cell; vertical-align: middle; height: 50px;">{{ $profile->first_name }} {{ $profile->last_name }}</p>
    </div>
</div>
</a>
</div>