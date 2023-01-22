<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function check_permission($module, $role) {
    $admin = ['anggota', 'simpanan_anggota', 'user_settings', 'notifications', 'master', 'posting_simpanan', 'ubah_simpanan', 'parameter_settings']; 
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
