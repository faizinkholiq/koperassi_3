<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function check_permission($module, $role) {
    $admin = ['anggota']; 
    $member = ['simpanan', 'pinjaman', 'laporan'];
    
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
