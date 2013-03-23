<section id="members">
    <div class="page-header">
        <h1>Members<small> of the fine [FGT]y team</small></h1>
    </div>
    <div class="row">
        <div class="span6">
            <div class="row">
                <div class="span0.1">
                    <img src="img/icons/terran-decal.png" width="25" />
                </div>
                <div class="span5"><h2>FGTbonywonix <small>Troy Pavlek</small></h2></div>
            </div>
            <ul class="thumbnails">
                <li class="span4">
                    <div class="thumbnail">
                        <img width="300" src="img/profile/bonnywonix.png" />
                    </div>
                </li>
            </ul>
        </div>
        <div class="span5 offset1">
            <div class="well"><h3>League: <small>Platinum</small></h3>
                <p>
                    I like my crayons, and my Nexus Four Superphone, and my magical meeting cards, and my Starcraft 2
                    Game. I did all the web-development for this site. I also exist on the internet, so you can see me
                    there too. Furthermore, I am
                    <a href="http://www.reddit.com/r/starcraft/comments/14rxdu/puppies_aint_a_game/">
                        definitely a fan of puppies
                    </a>
                </p>
                    <?php
                    // TODO make this modular
                    $latestReplays = $page->getDB()->getTopicsInForumFromUserByPage(4, 17, 0, 3);
                    if ($latestReplays['data']) {

                        echo "<strong>Latest Replays: </strong><br />";
                        foreach ($latestReplays['data'] as $topic) {
                            echo "<p><a class='btn btn-info btn-block' href='?page=viewTopic.php&tid=" . $topic->getTid() . "'>"
                                . $topic->getSubject() . "</a></p>";
                        }

                    }
                    ?>
                <div class="row-fluid">
                    <div class="span3">
                        <a href="http://twitter.com/ebonwumon">
                            <img src="img/icons/twitter-icon.png" width="50"/>
                        </a>
                    </div>
                    <div class="span3">
                        <a href="http://facebook.com/ebonwumon">
                            <img src="img/icons/fb-icon.png" width="50"/>
                        </a>
                    </div>
                    <div class="span3">
                        <a href="https://plus.google.com/107871029902972802193/posts">
                            <img src="img/icons/gplus-icon.png" width="50" />
                        </a>
                    </div>
                    <div class="span3">
                        <a href="http://sc2ranks.com/us/2275201/FGTbonywonix">
                            <img src="img/icons/sc2-icon.png" width="50" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="span6">
            <div class="row">
                <div class="span0.1">
                    <img src="img/icons/protoss-decal.png" width="20"/>
                </div>
                <div class="span5.9">
                    <h2>FGTcortstar <small>Cortland Davidson</small></h2>
                </div>
            </div>
            <ul class="thumbnails">
                <li class="span4">
                    <div class="thumbnail">
                        <img width="300" src="img/profile/cortstar.jpg" />
                    </div>
                </li>
            </ul>
        </div>
        <div class="span5 offset1">
            <div class="well">
                <h3>League: <small>Platinum</small></h3>
                <blockquote>
                    I volunteer as tribute! <small>Cortland Davidson</small>
                </blockquote>
                <?php
                // TODO make this modular
                $latestReplays = $page->getDB()->getTopicsInForumFromUserByPage(4, 20, 0, 3);
                if ($latestReplays['data']) {

                    echo "<strong>Latest Replays: </strong><br />";
                    foreach ($latestReplays['data'] as $topic) {
                        echo "<p><a class='btn btn-info btn-block' href='?page=viewTopic.php&tid=" . $topic->getTid() . "'>"
                            . $topic->getSubject() . "</a></p>";
                    }

                }
                ?>
                <div class="row">
                    <div class="span1">
                        <a href="https://www.facebook.com/Cortstar">
                            <img src="img/icons/fb-icon.png" width="50">
                        </a>
                    </div>
                    <div class="span1">
                        <a href="http://www.sc2ranks.com/us/3014719/Cortstar">
                            <img src="img/icons/sc2-icon.png" width="50" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr />

    <div class="row">
        <div class="span6">
            <div class="row">
                <div class="span0.1">
                    <img src="img/icons/terran-decal.png" width="25" />
                </div>
                <div class="span5">
                    <h2>FGTShuffle <small>Connell Parish</small></h2>
                </div>
            </div>
            <ul class="thumbnails">
                <li class="span4">
                    <div class="thumbnail">
                        <img width="300" src="img/profile/icekommander.jpg" />
                    </div>
                </li>
            </ul>
        </div>
        <div class="span5 offset1">
            <div class="well">
                <h3>League: <small>Masters</small></h3>
                <blockquote>
                    Harder, Better, Faster, Stronger <small>ConnellParish</small>
                </blockquote>
                <?php
                // TODO make this modular
                $latestReplays = $page->getDB()->getTopicsInForumFromUserByPage(4, 23, 0, 3);
                if ($latestReplays['data']) {

                    echo "<strong>Latest Replays: </strong><br />";
                    foreach ($latestReplays['data'] as $topic) {
                        echo "<p><a class='btn btn-info btn-block' href='?page=viewTopic.php&tid=" . $topic->getTid() . "'>"
                            . $topic->getSubject() . "</a></p>";
                    }

                }
                ?>
                <div class="row-fluid">
                    <div class="span3">
                        <a href="http://sc2ranks.com/us/1662338/Icekommander">
                            <img src="img/icons/sc2-icon.png" width="50" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr />

    <div class="row">
        <div class="span6">
            <div class="row">
                <div class="span0.1">
                    <img src="img/icons/protoss-decal.png" width="20"/>
                </div>
                <div class="span5.9">
                    <h2>FGThjklzEX <small>Andy Yao</small></h2>
                </div>
            </div>
            <ul class="thumbnails">
                <li class="span4">
                    <div class="thumbnail">
                        <img width="300" src="img/profile/hjklz.jpg" />
                    </div>
                </li>
            </ul>
        </div>
        <div class="span5 offset1">
            <div class="well">
                <h3>League: <small>Diamond</small></h3>
                <blockquote>
                    Do you think it's
                    possible to choose a username so obnoxious that your opponent will become completely distracted and
                    automatically lose the game?
                    <small>Andy Yao</small>
                </blockquote>
                <?php
                    $latestReplays = $page->getDB()->getTopicsInForumFromUserByPage(4, 24, 0, 3);
                    if ($latestReplays['data']) {

                        echo "<strong>Latest Replays: </strong>";
                        foreach ($latestReplays['data'] as $topic) {
                            echo "<a class='btn btn-info' href='?page=viewTopic.php&tid=" . $topic->getTid() . "'>"
                                . $topic->getSubject() . "</a>";
                        }

                    }
                ?>
                <div class="row-fluid">
                    <div class="span3">
                        <a href="http://www.sc2ranks.com/us/506160/hjklzEX">
                            <img src="img/icons/sc2-icon.png" width="50" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr />


</section>

