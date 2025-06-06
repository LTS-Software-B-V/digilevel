@component('mail::message')
# @lang('Hello!')

Er is ingelogd op uw account vanuit een nieuwe locatie of apparaat.

> **@lang('Account:')** {{ $account->email }}<br/>
> **@lang('Time:')** {{ $time->toCookieString() }}<br/>
> **@lang('IP Address:')** {{ $ipAddress }}<br/>
> **@lang('Browser:')** {{ $browser }}<br/>
@if ($location && $location['default'] === false)
> **@lang('Location:')** {{ $location['city'] ?? __('Unknown City') }}, {{ $location['state'], __('Unknown State') }}
@endif

@lang('If this was you, you can ignore this alert. If you suspect any suspicious activity on your account, please change your password.')

@lang('Regards,')<br/>
{{ config('app.name') }}
@endcomponent
