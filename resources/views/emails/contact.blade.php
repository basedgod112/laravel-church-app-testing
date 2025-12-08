<p>There is a new message from the contact form:</p>

<ul>
    <li><strong>Name:</strong> {{ $data['name'] }}</li>
    <li><strong>Email:</strong> {{ $data['email'] }}</li>
</ul>

<p><strong>Message:</strong></p>
<p>{!! nl2br(e($data['message'])) !!}</p>
