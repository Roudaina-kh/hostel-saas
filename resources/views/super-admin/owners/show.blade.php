<h1>Détail du propriétaire</h1>

<p><strong>Nom :</strong> {{ $owner->name }}</p>
<p><strong>Email :</strong> {{ $owner->email }}</p>

<h2>Hostels</h2>

<ul>
    @foreach($owner->hostels as $hostel)
        <li>{{ $hostel->name }}</li>
    @endforeach
</ul>