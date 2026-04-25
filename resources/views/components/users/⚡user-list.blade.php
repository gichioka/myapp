<div style="padding:20px">

<h1>ユーザー一覧</h1>

<table border="1" cellpadding="8">

<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Department</th>
<th>Employment</th>
<th>Retired</th>
</tr>
</thead>

<tbody>

@foreach($users as $user)

<tr>
<td>{{ $user->id }}</td>
<td>{{ $user->name }}</td>
<td>{{ $user->email }}</td>
<td>{{ $user->department }}</td>
<td>{{ $user->employment_type }}</td>
<td>{{ $user->is_retired ? '退職' : '在籍' }}</td>
</tr>

@endforeach

</tbody>

</table>

</div>