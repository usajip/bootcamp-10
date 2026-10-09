@extends('template.layout')
@section('title', 'Home')
@section('content')
    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Age</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user['name'] }}</td>
                <td>{{ $user['age'] }}</td>
                <td>{{ $user['address'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@push('styles')
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 8px;
        text-align: left;
    }
</style>
@endpush

@push('scripts')
<script>
    console.log('Custom JavaScript loaded');
</script>
@endpush