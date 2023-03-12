@component('mail::message')
# Hi {{$maildata['name']}},
Congratsluation, You have been Promoted to Pool {{$maildata['pool_no']}}, Amount $ {{$maildata['amount']}}.
<br>Thanks,<br>
{{ config('app.name') }}
@endcomponent
