<p>Dear {{ $data['name'] }},</p>

<p>Thank you for your message. Below is our reply:</p>

<p>{!! nl2br(e($data['reply'])) !!}</p>

<hr>
<p>Your original message:</p>
<p>{!! nl2br(e($data['original'])) !!}</p>

<p>Kind regards,<br>The team</p>
