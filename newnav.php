<link rel="stylesheet" href="/nav.css?<?php echo rand(0, 1000000); ?>">
<section class="navSlider">
    <a href="/"><img src="https://panel.arma-life.com/img/armalifelogoflat.png" alt="AL Logo"></a>
    <div class="spacer"></div>
    <div class="main">
        <?php if (Guard::init()->isStaff()): ?>
            <a href='<?php echo $url; ?>'><i class="fas fa-home"></i> <span>Dashboard</span></a>
            <a href='<?php echo $url; ?>logger'><i class="fas fa-clipboard"></i> <span>Log Case</span></a>
            <a onclick='openOverlay("#messages");'><i class="fas fa-comment-alt"></i> <span>Staff Chat</span></a>
            <a href='<?php echo $url; ?>policies'><i class="fas fa-book"></i> <span>Staff Policies</span></a>
            <a href='<?php echo $url; ?>meetings'><i class="far fa-calendar-alt"></i> <span>Staff Meetings</span></a>
            <a href='<?php echo $url; ?>game'><i class="fas fa-server"></i> <span>Game Panel</span></a>
            <a href='<?php echo $url; ?>notebook'><i class="fas fa-book-open"></i> <span>Notebook [BETA]</span></a>
            <?php if ($user->isSLT()): ?>
                <a href='<?php echo $url; ?>viewer'><i class="fas fa-eye"></i> <span>View Cases</span></a>
                <a href='<?php echo $url; ?>search?type=cases'><i class="fas fa-search"></i>
                    <span>Search Cases</span></a>
                <a href='<?php echo $url; ?>staff/'><i class="fas fa-clipboard-list"></i> <span>Manage Staff</span></a>
                <a href='<?php echo $url; ?>staff/overview'><i class="fas fa-info-circle"></i>
                    <span>Staff Overview</span></a>
                <a href='<?php echo $url; ?>staff/interviews'><i class="fas fa-microphone"></i>
                    <span>Staff Interviews</span></a>
                <a href='<?php echo $url; ?>staff/audit'><i class="fas fa-list-alt"></i> <span>Audit Log</span></a>
                <a href='<?php echo $url; ?>staff/audit_factions'><i class="fas fa-list-alt"></i> <span>Factions Audit Log</span></a>
            <?php endif; ?>

            <a href='<?php echo $url; ?>staff/statistics'><i class="fas fa-chart-line"></i> <span>Statistics</span></a>
            <a id="modalLaunch" launch="logout"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
            <div style="height: 58px;"></div>
            <a class="bottom" href="<?php echo $url; ?>me">
                <div class="spacer"></div>
                <i class="fas fa-user"></i> <span><?= $user->displayName(); ?></span>
            </a>
        <?php elseif(Guard::init()->isCommand()): ?>
            <a href='<?php echo $url; ?>game'><i class="fas fa-server"></i> <span>Game Panel</span></a>
            <a href='<?php echo $url; ?>meetings'><i class="far fa-calendar-alt"></i> <span>Meetings</span></a>
            <a href='<?php echo $url; ?>notebook'><i class="fas fa-book-open"></i> <span>Notebook [BETA]</span></a>
            <a href='<?php echo $url; ?>staff/audit_factions'><i class="fas fa-list-alt"></i> <span>Factions Audit Log</span></a>
            <a href='<?php echo $url; ?>staff/statistics'><i class="fas fa-chart-line"></i> <span>Statistics</span></a>
            <a id="modalLaunch" launch="logout"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
        <?php endif; ?>
    </div>
</section>
<div class="modal" id="logout">
    <button id="close">×</button>
    <div class="content" style="max-width: 400px;border-radius: 5px;">
        <h1>Are you sure?</h1>
        <div class="btnGroup">
            <button style="border-bottom-right-radius: 0;border-top-right-radius: 0;" onclick="closeAllModal();">No, I love this panel</button>
            <button style="border-bottom-left-radius: 0;border-top-left-radius: 0;" onclick="logout();">Yes, Logout</button>
        </div>
    </div>
</div>