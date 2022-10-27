<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['auth/login']					= 'Login/proses_login';
$route['logout']						= 'Login/logout';
$route['settings']						= 'Home/settings';
$route['list/petugas']					= 'Home/list_petugas';
$route['save/petugas']					= 'Home/save_petugas';
$route['update_foto/petugas/(:any)']	= 'Home/update_foto_profile/$1';
$route['delete/petugas/(:any)']			= 'Home/delete_petugas/$1';
$route['password/petugas/(:any)']		= 'Home/update_password_petugas/$1';
$route['delete_foto/petugas/(:any)']	= 'Home/delete_foto_profile/$1';
$route['list/kelas']					= 'Home/list_kelas';
$route['save/kelas']					= 'Home/save_kelas';
$route['delete/kelas/(:any)']			= 'Home/delete_kelas/$1';

$route['siswa']							= 'Siswa/index';
$route['list/siswa']					= 'Siswa/list_siswa';
$route['add/siswa']						= 'Siswa/add_edit_siswa';
$route['edit/siswa/(:any)']				= 'Siswa/add_edit_siswa/$1';
$route['save/siswa']					= 'Siswa/save_siswa';
$route['delete/siswa/(:any)']			= 'Siswa/delete_siswa/$1';

$route['buku']							= 'Buku/index';
$route['list/buku']						= 'Buku/list_buku';
$route['save/buku']						= 'Buku/save_buku';
$route['delete/buku/(:any)']			= 'Buku/delete_buku/$1';

$route['peminjaman']					= 'Peminjaman/index';
$route['list/peminjaman']				= 'Peminjaman/list_peminjaman';
$route['save/peminjaman']				= 'Peminjaman/save_peminjaman';
$route['update/pengembalian/(:any)']	= 'Peminjaman/update_pengembalian/$1';
$route['delete/peminjaman/(:any)']		= 'Peminjaman/delete_peminjaman/$1';