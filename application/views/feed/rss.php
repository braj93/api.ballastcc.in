<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
    <channel>
    <title><?php echo $feed_name; ?></title>
    <link><?php echo $feed_url; ?></link>
    <dc:rights>Copyright <?php echo gmdate("Y", time()); ?></dc:rights>
    <admin:generatorAgent rdf:resource="http://www.codeigniter.com/" />
    <?php foreach($users as $user){ ?>
        <item> 
            <title><?php echo $user['name']; ?></title>
            <first_letter><?php echo $user['first_letter']; ?></first_letter>
            <user_id><?php echo $user['user_id']; ?></user_id>
            <email><?php echo $user['email']; ?></email>
            <created_at_time_ago><?php echo $user['created_at_time_ago']; ?></created_at_time_ago>
            <pubDate><?php echo $user['created_at']; ?></pubDate>
        </item>
    <?php } ?>
    </channel>
</rss>