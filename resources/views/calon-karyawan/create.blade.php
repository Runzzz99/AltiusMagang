@extends('layouts.app')
@section('title', 'Tambah Calon Karyawan')

@section('content')
@include('calon-karyawan._form', ['calon' => null])
@endsection