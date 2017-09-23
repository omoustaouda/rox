        <div id="profilesummary" class="card mb-3">
            <h3 class="card-header"><?php echo $words->get('ProfileSummary'); ?>
                <?php if ($showEditLinks): ?>
                <span class="float-right">
                    <a href="editmyprofile/<?php echo $profile_language_code; ?>" class="btn btn-sm btn-primary"><?php echo $words->get('Edit'); ?></a>
                </span>
                <?php endif; ?>
            </h3>
            <div class="card-block p-2">
                <div class="card-text m-0">
                    <?php
                    $purifier = MOD_htmlpure::getAdvancedHtmlPurifier();
                    echo $purifier->purify(stripslashes($member->get_trad("ProfileSummary", $profile_language, true)));
                    ?>
                </div>
            </div>
        </div>

        <div id="languages" class="card mb-3">
            <h3 class="card-header"><?php echo $words->get('ProfileLanguagesSpoken'); ?>
                <?php if ($showEditLinks): ?>
                    <span class="float-right">
                    <a href="editmyprofile/<?php echo $profile_language_code; ?>" class="btn btn-sm btn-primary"><?php echo $words->get('Edit'); ?></a>
                </span>
                <?php endif; ?>
            </h3>
            <div class="card-block p-2">
                <div class="card-text m-0">
                    <ul>
                        <?php
                        foreach ($member->get_languages_spoken() as $lang) {

                        echo '<li>' . $words->get($lang->WordCode) . '<sup>' . $words->get("LanguageLevel_" . $lang->Level) . '</sup></li>';
                        }
                      ?>
                    </ul>
                </div>
            </div>
        </div>

        <?php
            if ($member->get_trad("Hobbies", $profile_language, true) != "" || $member->get_trad("Organizations", $profile_language, true) != "" || $member->get_trad("Books", $profile_language, true) != "" || $member->get_trad("Music", $profile_language, true) != "" || $member->get_trad("Movies", $profile_language, true) != ""){
        ?>

        <div id="interests" class="card mb-3">
            <h3 class="card-header"><?php echo $words->get('ProfileInterests'); ?>
                <?php if ($showEditLinks): ?>
                    <span class="float-right">
                    <a href="editmyprofile/<?php echo $profile_language_code; ?>#!profileinterests" class="btn btn-sm btn-primary"><?php echo $words->get('Edit'); ?></a>
                </span>
                <?php endif; ?>
            </h3>
            <div class="card-block p-2">
                <div class="card-text m-0">
                <dl>
                    <?php
                    if ($member->get_trad("Hobbies", $profile_language, true) != "") {
                        echo '<dt>' . $words->get('ProfileHobbies') . '</dt>';
                        echo '<dd>' . $purifier->purify($member->get_trad("Hobbies", $profile_language, true)) . '</dd>';
                    }

                    if ($member->get_trad("Books", $profile_language, true) != "") {
                        echo '<dt>' . $words->get('ProfileBooks') . '</dt>';
                        echo '<dd>' . $purifier->purify($member->get_trad("Books", $profile_language, true)) . '</dd>';
                    }

                    if ($member->get_trad("Music", $profile_language, true) != "") {
                        echo '<dt>' . $words->get('ProfileMusic') . '</dt>';
                        echo '<dd>' . $purifier->purify($member->get_trad("Music", $profile_language, true)) . '</dd>';
                    }

                    if ($member->get_trad("Movies", $profile_language, true) != "") {
                        echo '<dt>' . $words->get('ProfileMovies') . '</dt>';
                        echo '<dd>' . $purifier->purify($member->get_trad("Movies", $profile_language, true)) . '</dd>';
                    }

                    if ($member->get_trad("Organizations", $profile_language, true) != "") {
                        echo '<dt>' . $words->get('ProfileOrganizations') . '</dt>';
                        echo '<dd>' . $purifier->purify($member->get_trad("Organizations", $profile_language, true)) . '</dd>';
                    }
                    ?>
                </dl>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php
            if ($member->get_trad("PastTrips", $profile_language, true) != "" || $member->get_trad("PlannedTrips", $profile_language, true) != "") {
                ?>

                <div id="travel" class="card mb-3">
                    <h3 class="card-header"><?php echo $words->get('ProfileTravelExperience'); ?>
                        <?php if ($showEditLinks): ?>
                            <span class="float-right">
                    <a href="editmyprofile/<?php echo $profile_language_code; ?>#!profileinterests" class="btn btn-sm btn-primary"><?php echo $words->get('Edit'); ?></a>
                </span>
                        <?php endif; ?>
                    </h3>
                    <div class="card-block p-2">
                        <div class="card-text m-0">
                            <dl>
                                <dt><?php echo $words->get('ProfilePastTrips'); ?>:</dt>
                                <dd><?php echo $purifier->purify($member->get_trad("PastTrips", $profile_language, true)); ?></dd>
                                <dt><?php echo $words->get('ProfilePlannedTrips'); ?>:</dt>
                                <dd><?php echo $purifier->purify($member->get_trad("PlannedTrips", $profile_language, true)); ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <?php
                    }


        // display my groups, if there are any
        $my_groups = $member->getGroups();
        if (!empty($my_groups)){ ?>



        <div id="groups" class="card mb-3 p-0">
            <h3 class="card-header"><?php echo $words->get('ProfileGroups'); ?>
                <?php if ($showEditLinks): ?>
                    <span class="float-right">
                    <a href="/groups/mygroups" class="btn btn-sm btn-primary"><?php echo $words->get('Edit'); ?></a>
                </span>
                <?php endif; ?>
            </h3>
            <div class="card-block p-2">
                <div class="card-text m-0">

                    <?php
                    // display my groups, if there are any
                    for ($i = 0; $i < count($my_groups) && $i < 5; $i++) :
                        $group_img = ((strlen($my_groups[$i]->Picture) > 0) ? "groups/thumbimg/{$my_groups[$i]->getPKValue()}" : 'images/icons/group.png');
                        $group_id = $my_groups[$i]->id;
                        $group_name = htmlspecialchars($my_groups[$i]->Name, ENT_QUOTES);
                        $comment = $purifier->purify($words->mInTrad($member->getGroupMembership($my_groups[$i])->Comment, $profile_language));
                        ?>
                        <div class="mb-3 d-flex d-column">
                            <div>
                                <a href="groups/<? echo $group_id; ?>">
                                <img class="framed float-left mr-2" width="50px" height="50px" alt="Group"
                                     src="<? echo $group_img; ?>"/>
                            </a>
                            </div>
                            <div>
                                <h4 class="m-0"><a href="groups/<? echo $group_id; ?>"><? echo $group_name; ?></a></h4>
                                <p class="m-0"><? echo $comment; ?></p>
                            </div>  <!-- groupinfo -->
                        </div>
                        <?php
                    endfor;
                    if (count($my_groups) > 5) :
                        echo '<p class="float-right"><a href="members/' . $member->Username . '/groups">' . $words->get('GroupsAllMyLink') . '</a></p>';
                    endif;
                    ?>
                </div>
            </div>
        </div>
        <? } ?>

        <?
        if ($this->model->getLoggedInMember() && !$this->passedAway){ ?>


            <div class="card mb-3">
                <h3 class="card-header"><?php echo $words->get('ContactInfo'); ?>
                    <?php if ($showEditLinks): ?>
                        <span class="float-right">
                    <a href="editmyprofile/<?php echo $profile_language_code; ?>#!contactinfo" class="btn btn-sm btn-primary"><?php echo $words->get('Edit'); ?></a>
                </span>
                    <?php endif; ?>
                </h3>
                <div class="card-block p-2">
                    <div class="card-text m-0">
                        <dl id="address">
                            <dt><?php echo $words->get('Name'); ?>:</dt>
                            <dd><?php echo $member->name ?></dd>

                            <dt><?php echo $words->get('Address'); ?>:</dt>
                            <dd><?php echo $member->street ?><br>
                                <?php echo $member->zip ?>
                                <?php echo $member->city ?><br>
                                <?php echo $member->country ?>
                            </dd>

                            <?php
                            if ($phones = $member->phone) {
                                echo '<dt>' . $words->get('ProfilePhone') .':</dt>';
                                foreach ($phones as $phone => $value) {
                                    echo '<dd>' . $words->get('Profile' . $phone) . ': ' . $value . '</dd>';
                                }
                            }

                            if (!empty($website)) {
                                $sites = explode(" ", str_replace(array("\r\n", "\r", "\n"), " ", $member->WebSite));
                                echo '<dt>' . $words->get('Website') . ':</dt>';
                                foreach ($sites as $site) {
                                    $site = str_replace(array('http://', 'https://'), '', $site);
                                    echo '<dd><a href="http://' . $site . '">' . $site . '</a></dd>';
                                }
                            }
                            if ($member->hasMessengers()) {
                                ?>
                                <dt><?php echo $words->get('Messenger'); ?>:</dt>
                                <dd>
                                    <?php
                                    foreach ($messengers as $m) {
                                        if (isset($m["address"]) && $m["address"] != '')
                                            echo "<img src='" . PVars::getObj('env')->baseuri . "images/icons/icons1616/" . $m["image"] . "' width='16' height='16' title='" . $m["network"] . "' alt='" . $m["network"] . "' /> " . $m["network"] . ": " . $m["address"] . "<br>";
                                    }
                                    ?>
                                </dd>
                            <?php } ?>
                        </dl>
                    </div>
                </div>
            </div>
        <? } ?>