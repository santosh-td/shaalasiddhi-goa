<?php
/*
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 * 
 * Perpose: Manage settings module
 */
class settingsController extends controller {

    function settingsAction() {
        if (in_array("manage_app_settings", $this->user['capabilities'])) {
            if (!empty($_POST['update']) && $_POST['update'] == 1 && !empty($_POST['roles']) && is_array($_POST['roles'])) {
                $roles = $this->userModel->getRolesWithCaps();
                $pRoles = $_POST['roles'];
                foreach ($roles as $role) {
                    $cCaps = $role['cap_ids'] != "" ? explode(",", $role['cap_ids']) : array();
                    $capToBeAdded = array();
                    $capToBeRemoved = array();
                    if (isset($pRoles[$role['role_id']])) {
                        $commonCaps = array_intersect($pRoles[$role['role_id']], $cCaps);
                        $capToBeAdded = array_diff($pRoles[$role['role_id']], $commonCaps);
                        $capToBeRemoved = array_diff($cCaps, $commonCaps);
                    } else {
                        $capToBeRemoved = $cCaps;
                    }
                    foreach ($capToBeAdded as $cap_id) {
                        $this->userModel->addCapabilityToRole($role['role_id'], $cap_id);
                    }
                    foreach ($capToBeRemoved as $cap_id) {
                        $this->userModel->removeCapabilityFromRole($role['role_id'], $cap_id);
                    }
                }
            }
            $this->set("roles", $this->userModel->getRolesWithCaps());
            $this->set("capabilities", $this->userModel->getCapabilities());
        } else
            $this->_notPermitted = 1;
    }

}
