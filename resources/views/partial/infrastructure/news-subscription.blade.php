<div class="col-12 news">
    <a style="display: block; margin-bottom: 10px; height: 60px;" href="/profile/{{ $subscriber->id }}">
        <div class="row">
            <div class="col-4" style="padding-right: 0;">
                <div>
                    <img style="max-width: 100%;  -webkit-border-radius: 50%;-moz-border-radius: 50%;border-radius: 50%;" src="{{ $subscriber->photo }}" alt="{{ $subscriber->first_name }} {{ $subscriber->last_name }}">
                </div>
            </div>
            <div class="col-8">
                <p style="margin: 0;display: table-cell; vertical-align: middle; height: 50px;"><span class="name">{{ $subscriber->first_name }} {{ $subscriber->last_name }}</span> just subscribe on your account.</p>
            </div>
        </div>
    </a>
</div>