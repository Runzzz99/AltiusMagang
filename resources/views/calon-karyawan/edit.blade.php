@extends('layouts.app')
@section('title', 'Ubah Calon Karyawan')

@section('content')
@include('calon-karyawan._form', ['calon' => $calon])
@endsection