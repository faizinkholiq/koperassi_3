<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function check_permission($module, $role) {
    $admin = ['anggota', 'simpanan_anggota', 'user_settings', 'notifications', 'master', 'posting_simpanan', 'ubah_simpanan', 'parameter_sistem', 'kas', 'report']; 
    $member = ['simpanan', 'pinjaman', 'laporan', 'anggota_settings', 'user_settings', 'notifications', 'ubah_simpanan', 'penarikan_simpanan'];

    switch ($role) {
        case 1:
            if (in_array($module, $admin)){
                return true;
            }else{
                return false;
            }
            break;
        
        case 2:
            if (in_array($module, $member)){
                return true;
            }else{
                return false;
            }
            break;
        
        default:
            return false;
            break;
    }
}

function get_alphabet_list()
{
	// list letter alphabet
	$letter = range('A', 'Z'); //alphabets, index 0 - 25
	$letters = $letter;

	foreach($letter as $i => $let) {
		foreach($letter as $l) {
			$letters[] = $let . $l; // it will make 'AA' until 'ZZ'
		}
	}

	return $letters;
}