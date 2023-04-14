<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function check_permission($module, $role) {
    $admin = ['anggota', 'simpanan_anggota', 'user_settings', 'notifications', 'master', 'posting_simpanan', 'ubah_simpanan', 'penarikan_simpanan', 'parameter_sistem', 'kas', 'report', 'pinjaman']; 
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

function rupiah($num){
    if (!empty($num) && $num != 0){
        $format_rupiah = "Rp" . number_format($num,2,',','.');
    }else{
        $format_rupiah = 0;
    }
    return $format_rupiah;
}

function PMT($interest,$num_of_payments,$PV,$FV = 0.00, $Type = 0){
    $xp=pow((1+$interest),$num_of_payments);
    return
        ($PV* $interest*$xp/($xp-1)+$interest/($xp-1)*$FV)*
        ($Type==0 ? 1 : 1/($interest+1));
}