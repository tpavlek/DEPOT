<section id="home" xmlns="http://www.w3.org/1999/html">
    <div class="row">
        <div class="span6 offset3" >
            <img src="img/fgt_main_logo.png" />
        </div>
    </div>
</section>
<section id="about">
    <div class="page-header">
        <h1>About [FGT]<small>For Great Times!</small></h1>
    </div>
    <div class="row">
        <!--<div class="span4"><h2>History</h2>
          <p>Team [FGT] was created one dark and stormy evening on the night of February 18th, 2012, through the ingenius naming capabilites of FGTbonywonix.
      Shortly thereafter, [FGT] was asked to represent the Greater Edmonton Area, and Canada as a whole in the Olympics, a relatively non-challenging task, easily taking Gold
      in all their Starcraft related events. However nice the Olympics win may be, it would not last, for the team saw their potential, and retaining their amateur status was
      simply impossible to accomplish. After playing Kim Jong-Un in a showmatch, the wager of which being nuclear disarmament of North Korea, the team proceeded to use their
    contacts with the American government to acquisition nuclear weaponry, and assert a hostile and quick takeover of the now non-nuclear North Korea. The large standing army of North Korea
    rapidly surrendered, but their skills would be greatly required later, when South Korea, distraught with jealousy as the GSL took up residence in their neighbour to the North (where all the greatest
    talet now obviously resided) decided to wage war. Lines between reality and video games became blurred as standing armies duked it out underneath holographic carriers and vikings, a
    showmatch-enhancing technology developed with ease by the resident genius FGTbonywonix. South Korea soon fell, in a close game between FGTShuffle and IMMVP, but ultimately an inevitible
    outcome. That brings us to today. Next, the world.
          </p>
        </div>-->
        <div class="span8">
            <h2>The Team</h2>
            Team [FGT] started in Wings of Liberty as a clan designed to sweep the entire Starcraft II scene by storm.
            The teams roster boasts some of the best players available in the respective homes of the players that are
            on the team, and that's saying something, probably. Further the times that the players have are greater than
            or equal two the times that they would have if they weren't on the team in most cases, except the cases where
            this lemma does not hold.
            <p>
            <blockquote>
                Most assuredly the team has had some success as measured against the metrics posted by similar teams
                named [FGT] in the Greater Edmonton Area in the fiscal quarters that the team named [FGT] has existed
                under the current management that is boasting of this success.
                <small>Troy "bonywonix" Pavlek, 2013</small>
            </blockquote>
            </p>

            We are coming. Expect us. Fear us. Please don't take our ladder points.
        </div>
        <div class="span4">
            <div class="thumbnail"><img src="img/kinder.jpg" />
            </div>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="span12">
            <div class="hero-unit">
                <?php
                /** invokes the latest replay for embedding on the main page
                 For the time being the SC2 Replays forum is hardcoded with the FID of 4
                 */
                require_once('obj/forum/Topic.php');
                require_once('fragments/replayBox.php');
                $latestReplay = new Topic($page->getDB()->getLastTopic(4)['data']['id']);
                $replayBox = new replayBox($latestReplay->getReplay());
                ?>
                <h1>Latest Replay</h1>
                <p>
                    Our last replay is from <?php echo $latestReplay->getAuthor(); ?>. So go ahead and check it out and
                    feel free to leave a comment or two on the thread!
                </p>
                <strong><?php echo $latestReplay->getSubject(); ?></strong>
                <?php print $replayBox->getBox(); ?>
                <p>
                    <a href='?page=viewTopic.php&tid=<?php echo $latestReplay->getTid(); ?>' class='btn btn-primary'>
                        View Thread
                    </a>
                </p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="span12"><h2>From the Blog:</h2>
            <script src='http://feeds.feedburner.com/depotwarehouse/fiDO?format=sigpro' type="text/javascript"> </script>
            <a class="btn btn-success btn-block btn-large" href="http://eepurl.com/ucHgD">Subscribe to [FGT] Mail</a>
        </div>
    </div>
</section>

