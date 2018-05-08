<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

include_once __DIR__ . "/fontawesome5_iconlist.php";
?>
<div class="card">
    <div class="input-group" id="iconsearchrow">
        <div class="input-group-prepend px-2">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
        </div>
        <input type="text" id="icon_search" class="form-control" />
    </div>
    <div class="icon_bin">
        <label title="No Icon" data-search="no icon blank empty none" data-icon="">
            <input type="radio" class="iconselector_radio" name="selectedicon" value="" />
            <div class="card icon">
                <div class="card-body m-0 p-1 text-red">
                    <span class="fas fa-times fa-fw"></span>
                </div>
            </div>
        </label>
        <?php
        foreach ($FONTAWESOME as $fa => $info) {
            ?>
            <label title="<?php echo $info["label"]; ?>" data-search="<?php echo implode(" ", $info["search"]) . ' ' . strtolower($info["label"]); ?>" data-icon="<?php echo $fa; ?>">
                <input type="radio" class="iconselector_radio" name="selectedicon" value="<?php echo $fa; ?>" />
                <div class="card icon">
                    <div class="card-body m-0 p-1">
                        <span class="<?php echo $fa; ?> fa-fw"></span>
                    </div>
                </div>
            </label>
            <?php
        }
        ?>
    </div>
</div>