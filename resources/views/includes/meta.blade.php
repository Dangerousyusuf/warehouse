<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--favicon-->
  <link rel="icon" href="{{url('assets/images/telogo.png')}}" type="image/png">
  <!-- loader-->
  <link href="{{url('assets/css/pace.min.css')}}" rel="stylesheet">
  <script src="{{url('assets/js/pace.min.js')}}"></script>
  
  <!--plugins-->
  <link href="{{url('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{url('assets/plugins/metismenu/metisMenu.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{url('assets/plugins/metismenu/mm-vertical.css')}}">
  <link rel="stylesheet" type="text/css" href="{{url('assets/plugins/simplebar/css/simplebar.css')}}">
  <link rel="stylesheet" href="{{url('assets/plugins/notifications/css/lobibox.min.css')}}">
  <link href="{{url('assets/plugins/input-tags/css/tagsinput.css')}}" rel="stylesheet">
  <link href="{{url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
  <link href="{{url('assets/plugins/fancy-file-uploader/fancy_fileupload.css')}}" rel="stylesheet">
  <link href="{{url('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
  
  <link rel="stylesheet" href="{{url('assets/css/bootstrap.min.css')}}">
  
  <!--main css-->
  <link rel="stylesheet" href="{{url('assets/css/bootstrap-extended.css')}}">
  <link href="{{url('sass/main.css')}}" rel="stylesheet">
  <link href="{{url('sass/dark-theme.css')}}" rel="stylesheet">
  <link href="{{url('sass/blue-theme.css')}}" rel="stylesheet">
  <link href="{{url('sass/semi-dark.css')}}" rel="stylesheet">
  <link href="{{url('sass/bordered-theme.css')}}" rel="stylesheet">
  <link href="{{url('sass/responsive.css')}}" rel="stylesheet">
  
  <!--bootstrap css-->
  <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</head>
@auth
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            applyTheme('{{ Auth::user()->theme }}');
        });

        function applyTheme(theme) {
            document.documentElement.setAttribute('data-bs-theme', theme);
            console.log('Applying theme:', theme);
        }
    </script>
@endauth

@php
    $theme = Auth::check() ? Auth::user()->theme : 'light';
@endphp
<html lang="tr" data-bs-theme="{{ $theme }}">

@if(session('success'))
    <script>
    document.addEventListener('DOMContentLoaded', function () {
      successToast("{{ session('success') }}");
    });
    </script>
  @endif

  @if(session('error'))
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        errorToast("{{ session('error') }}");
    });
    </script>
  @endif
