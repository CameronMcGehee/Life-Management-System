<?php

    return array(
        'devWelcomeInfo' => '
            <h3>Welcome to LifeMS!</h3>

            <p>LifeMS will be an easy-to-use yet fully functional web app for fully automating a lawn/landscaping service workspace. It will offer:</p>

            <div style="margin-left: 5ch;">
                <li>Contact Management</li>
                <li>Property Management</li>
                <li>CalendarEvent Management</li>
                <li>Invoice Management</li>
                <li>Estimate Management</li>
                <li>Equipment/Inventory Tracking</li>
                <li>Chemical Inventory and Application Tracking</li>
                <li>Staff Management</li>
                <li>Crew Management</li>
                <li>Payroll Tracking</li>
                <li>A custom homepage for your workspace and contact portal for your contacts to access their scheduled calendarEvents, invoices, pay online, and more.</li>
            </div>

            <p>Designed to be as speedy as possible, there will be limited libraries used, no bloated javascript frameworks etc. It will be mostly PHP/HTML/CSS from scratch apart from very small amounts of jQuery for working with dynamic HTML (popups, for example) and async functions.</p>

            <p>LifeMS is styled in mobile view as 1st priority, then desktop. Eventualy an app will be made that will load the website, but in fullscreen for professionalism. That way, mobile workers/crews will all have a good experience when out on the calendarEvent accessing the site/app. A desktop app that loads the site will also be made available at some point. These will all have their own branches.</p>
        ',
        'workspaceCreated' => '<h3>Congrats! [[workspaceName]] has been created!</h3>',
        'noteDeleted' => '<p>Successfully deleted "[[noteTitle]]".</p>',
        'contactDeleted' => '<p>Successfully deleted "[[contactName]]".</p>',
        'invoiceDeleted' => '<p>Successfully deleted invoice "[[invoiceId]]".</p>',
        'calendarEventDeleted' => '<p>Successfully deleted calendarEvent "[[calendarEventName]]".</p>',
        'estimateDeleted' => '<p>Successfully deleted estimate "[[estimateId]]".</p>'
    );

?>
