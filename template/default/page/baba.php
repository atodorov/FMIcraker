<p>;ks;mf</p>

<p>d</p>

<p>;</p>

<p>{</p>

<hr />
<table border=\"1\" cellpadding=\"1\" cellspacing=\"1\" style=\"width:500px\">
	<tbody>
		<tr>
			<td>ljlsdlb;dbvnsdn;n;bsdsn;sn; n ; nkdn vvsds&nbsp;&nbsp;&nbsp; ssdkvsk;dk;sdkdh&nbsp;&nbsp;&nbsp; d s dhhksdhdh</td>
			<td>hhshshsdhsdhddlfsdsd&nbsp;&nbsp; ooddshds ddshsh&nbsp; dshfhsshsipfsh&nbsp;&nbsp; hffhghshgghsdhhg</td>
		</tr>
		<tr>
			<td>idfjlshsdd&nbsp; h h hhssdh jgggkghfiry j vtu754 7y u-g g utu9&nbsp; y9 8 ty0 g9UGU9G9</td>
			<td>UGUOGIGHUOG</td>
		</tr>
		<tr>
			<td>uogu ogigjryghjhghhhg</td>
			<td>gugguofutdyfbvuciuthg&nbsp; uy ft5 t uig y i 8 5576807y ug gh&nbsp; 6r7 t 97g 75 dfg ut 55e5 7tt&nbsp; f6ry tt&nbsp; g g</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p><a id=\"http://www.new.spiderace.com\" name=\"http://www.new.spiderace.com\"></a></p>

<p>&nbsp;</p>

<p><a href=\"http://newspiderac.ecom/pages/\">http://newspiderac.ecom/pages/</a></p>

<p>&nbsp;</p>

<h1><a href=\"http://blog.mongolab.com/2013/03/replication-lag-the-facts-of-life/\">Replication Lag &amp; The Facts of Life</a></h1>

<p>By <a href=\"http://blog.mongolab.com/author/dampier/\">dampier</a> on 2013/03/18 in <a href=\"http://blog.mongolab.com/category/mongodb/\">MongoDB</a></p>

<p>So you&rsquo;re checking in on your latest awesome application one day &mdash; it&rsquo;s really getting traction! You&rsquo;re proud of its uptime record, thanks in part to the MongoDB replica set underneath it. But now &hellip; something&rsquo;s wrong. Users are complaining that some of their data has gone missing. Others are noticing stuff they deleted has suddenly reappeared. What&rsquo;s going on?!?</p>

<p>Don&rsquo;t worry&hellip; we&rsquo;ll get to the bottom of this! In doing so, we&rsquo;ll examine a source of risk that&rsquo;s easy to overlook in a MongoDB application:&nbsp;<em>replication lag &mdash;</em>&nbsp;what it means, why it happens, and what you can do about it.</p>

<p>&nbsp;</p>

<p>Here&rsquo;s what we&rsquo;re going to cover:</p>

<ul>
	<li><a href=\"http://blog.mongolab.com/2013/03/replication-lag-the-facts-of-life/#What_is_replication_lag\">What is replication lag?</a></li>
	<li><a href=\"http://blog.mongolab.com/2013/03/replication-lag-the-facts-of-life/#Why_is_lag_problematic\">Why is lag problematic?</a></li>
	<li><a href=\"http://blog.mongolab.com/2013/03/replication-lag-the-facts-of-life/#What_causes_a_secondary_to_fall_behind\">What causes a secondary to fall behind?</a></li>
	<li><a href=\"http://blog.mongolab.com/2013/03/replication-lag-the-facts-of-life/#How_do_I_measure_lag\">How do I measure lag?</a></li>
	<li><a href=\"http://blog.mongolab.com/2013/03/replication-lag-the-facts-of-life/#How_do_I_monitor_for_lag\">How do I monitor for lag?</a></li>
	<li><a href=\"http://blog.mongolab.com/2013/03/replication-lag-the-facts-of-life/#What_can_I_do_to_minimize_lag\">What can I do to minimize lag?</a>
	<ul>
		<li><a href=\"http://blog.mongolab.com/2013/03/replication-lag-the-facts-of-life/#Tip_1_Make_sure_your_secondary_has_enough_horsepower\">Tip #1: Make sure your secondary has enough horsepower</a></li>
		<li><a href=\"http://blog.mongolab.com/2013/03/replication-lag-the-facts-of-life/#Tip_2_Consider_adjusting_your_write_concern\">Tip #2: Consider adjusting your write concern</a></li>
		<li><a href=\"http://blog.mongolab.com/2013/03/replication-lag-the-facts-of-life/#Tip_3_Plan_for_index_builds\">Tip #3: Plan for index builds</a></li>
		<li><a href=\"http://blog.mongolab.com/2013/03/replication-lag-the-facts-of-life/#Tip_4_Take_backups_without_blocking\">Tip #4: Take backups without blocking</a></li>
		<li><a href=\"http://blog.mongolab.com/2013/03/replication-lag-the-facts-of-life/#Tip_5_Be_sure_capped_collections_have_an__id_field_a_unique_index\">Tip #5: Be sure capped collections have an _id field &amp; a unique index</a></li>
		<li><a href=\"http://blog.mongolab.com/2013/03/replication-lag-the-facts-of-life/#Tip_6_Check_for_replication_errors\">Tip #6: Check for replication errors</a></li>
	</ul>
	</li>
	<li><a href=\"http://blog.mongolab.com/2013/03/replication-lag-the-facts-of-life/#Don8217t_let_replication_lag_take_you_by_surprise\">Don&rsquo;t let replication lag take you by surprise.</a></li>
</ul>

<p>Continuing this cautionary tale&hellip; Seriously, wtf?! You were doing everything right!</p>

<p>Using MongoDB with a well-designed schema and lovingly-tuned indexes, your application back-end has been handling thousands of transactions per second without breaking a sweat. You&rsquo;ve got multiple nodes arranged in a replica set with no single point of failure. Your application tier&rsquo;s Mongo driver connections are aware of the replica set and can follow changes in the PRIMARY node during failover. All critical writes are &ldquo;safe&rdquo; writes. Your app has been up without interruption for almost six months now! <em>How could this have happened?</em></p>

<p>This unsettling situation has the hallmarks of an insidious foe in realm of high-availability data stewardship: <strong>unchecked replication lag</strong>.</p>

<p>Closely monitoring a MongoDB replica set for replication lag is critical.</p>

<h2>What is replication lag?</h2>

<p>As you probably know, like many data stores MongoDB relies on&nbsp;<em>replication</em>&nbsp;&mdash; making redundant copies of data &mdash; to meet design goals around availability.</p>

<p><a href=\"http://en.wikipedia.org/wiki/The_Facts_of_Life_%28TV_series%29\" target=\"_blank\"><img alt=\"The Facts of Life\" src=\"http://blog.mongolab.com/wp-content/uploads/2013/03/titlecard12-ytv-wherearetheynow-factsoflife-jpg_203702-240x300.jpg\" style=\"height:300px; width:240px\" /></a>In a perfect world, data replication would be instantaneous; but in reality, thanks to pesky laws of physics, some delay is inevitable &mdash; it&rsquo;s a&nbsp;<strong>fact of life</strong>. We need to be able to reason about how it affects us so as to manage around the phenomenon appropriately. Let&rsquo;s start with definitions&hellip;</p>

<p>For a given secondary node, <strong><em>replication lag</em></strong> is the delay between the time an operation occurs on the primary and the time that same operation gets applied on the secondary.</p>

<p>For the replica set as a whole, replication lag is (for most purposes)&nbsp;the smallest replication lag found among all its secondary nodes.</p>

<p>In a smoothly running replica set, all secondaries closely follow changes on the primary, fetching each group of operations from its <a href=\"http://docs.mongodb.org/manual/reference/glossary/#term-oplog\" target=\"_blank\">oplog</a> and replaying them approximately as fast as they occur. That is, replication lag remains as close to zero as possible. Reads from any node are then reasonably consistent; and, should the current primary become unavailable, the secondary that assumes the PRIMARY role will be able to serve to clients a dataset that is almost identical to the original.</p>

<p>For a variety of reasons, however, secondaries may fall behind. Sometimes elevated replication lag is transient and will remedy itself without intervention. Other times, replication lag remains high or continues to rise, indicating a systemic problem that needs to be addressed. In either case, the larger the replication lag grows and the longer it remains that way, the more exposure your database has to the associated risks.</p>

<h2>Why is lag problematic?</h2>

<p>Significant replication lag creates failure modes that can be problematic for a MongoDB database deployment that is meant to be highly available.&nbsp; Here&rsquo;s why:</p>

<ul>
	<li>If your replica set fails over to a secondary that is significantly behind the primary, a lot of un-replicated data may be on the original primary that will need to be manually reconciled. This will be painful or impossible if the original primary is unrecoverable.</li>
	<li>If the failed primary cannot be recovered quickly, you may be forced to run on a node whose data is not up-to-date, or forced to take down your database altogether until the primary can be recovered.</li>
	<li>If you have only one secondary, and it falls farther behind than the earliest history retained in the primary&rsquo;s oplog,&nbsp;your secondary will require a full resynchronization from the primary.
	<ul>
		<li>During the resync, your cluster will lack the redundancy of a valid secondary; the cluster will not return to high availability until the entire data set is copied.</li>
		<li>If you only take backups from your secondary (which we highly recommend), backups must be suspended for the duration of the resync.</li>
	</ul>
	</li>
	<li>Replication lag makes it more likely that results of any read operations distributed across secondaries will be inconsistent.</li>
	<li>A &ldquo;safe&rdquo; write with &lsquo;w&rsquo; &gt; 1 &mdash; i.e., requiring multiple nodes acknowledge the write before it returns &mdash; will incur latency proportional to the current replication lag, and/or may time out.</li>
</ul>

<p>Strictly speaking, the problem of replication lag is distinct from the problem of data durability. But as the last point above regarding multi-node write concern illustrates, the two concepts are most certainly linked. Data that has not yet been replicated is not completely protected from single-node failure; and client writes specified to be safe from single-node failure must block until replication catches up to them.</p>

<h2>What causes a secondary to fall behind?</h2>

<p>In general, a secondary falls behind on replication any time it cannot keep up with the rate at which the primary is writing data. Some common causes:</p>

<p>Secondary is weak</p>

<p>To have the best chance of keeping up, a secondary host should match the primary host&rsquo;s specs for CPU, disk IOPS, and network I/O. If it&rsquo;s outmatched by the primary on any of these specs, a secondary may fall behind during periods of sustained write activity. Depending on load this will, at best, create brief excursions in replication lag and, at worst, cause the secondary to fall irretrievably behind.</p>

<p>Bursty writes</p>

<p>In the wake of a burst of write activity on the primary, a secondary may not be able to fetch and apply the ops quickly enough. If the secondary is underpowered, this effect can be quite dramatic. But even when the nodes have evenly matched specs, such a situation is possible. For example, a command like:</p>

<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	<tbody>
		<tr>
			<td>
			<p><code>db.coll.update({x: 7}, {$set: {y: 42}}, {multi: true}}</code></p>
			</td>
		</tr>
	</tbody>
</table>

<p>can place an untold number of separate &ldquo;update&rdquo; ops in the primary&rsquo;s oplog. To keep up, a secondary must fetch those ops (max 4MB at a time for each <code>getMore</code> command!), read into RAM any index and data pages necessary to satisfy each <code>_id</code> lookup (remember: each oplog entry references a single target document by <code>_id</code>; the original query about &ldquo;x&rdquo; is never directly reflected the oplog), and finally perform the update op, altering the document and placing the corresponding entry into its oplog; and it must do all this in the same amount of time that the primary does merely the last step. Multiplied by a large enough number of ops, that disparity can amount to a noticeable lag.</p>

<p>Map/reduce output</p>

<p>A specific type of the extreme write burst scenario might be a command like:</p>

<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	<tbody>
		<tr>
			<td>
			<p><code>db.coll.mapReduce( ... { out: other_coll ... })</code></p>
			</td>
		</tr>
	</tbody>
</table>

<p>From the point of view of the oplog, the entire output collection basically materializes at once, from which point the replication to the secondary plays out as above.</p>

<p>Index build</p>

<p>It may surprise you to learn that, even if you build an index in the background on the primary, it will be built in the foreground on each secondary. There is currently no way to build indexes in the background on secondary nodes (cf. <a href=\"https://jira.mongodb.org/browse/SERVER-2771\" target=\"_blank\">SERVER-2771</a>). Therefore, whenever a secondary builds an index, it will <strong>block all other operations</strong>, including replication, for the duration. If the index builds quickly, this may not be a problem; but long-running index builds can swiftly manifest as significant replication lag.</p>

<p>Secondary is locked for backup</p>

<p>One of the <a href=\"http://docs.mongodb.org/manual/administration/backups/#replica-set-backups\" target=\"_blank\">suggested methods for backing up data in a replica set</a> involves explicitly locking a secondary against changes while the backup is taken. Assuming the primary is still conducting business as usual, of course replication lag will climb until the backup is complete and the lock is released.</p>

<p>Secondary is offline</p>

<p>Similarly, if the secondary is not running or cannot reach the primary for whatever reason, it cannot make progress against the replication backlog. When it rejoins the replica set, the replication lag will naturally reflect the time spent away.</p>

<h2>How do I measure lag?</h2>

<p>Run the <tt>db.printSlaveReplicationInfo()</tt> command</p>

<p>To determine the current replication lag of your replica set, you can use the <a href=\"http://docs.mongodb.org/manual/mongo/\" target=\"_blank\"><code>mongo</code> shell</a> and run the <code><strong>db.printSlaveReplicationInfo()</strong></code> command.</p>

<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	<tbody>
		<tr>
			<td>
			<p><code>rs-ds046297:PRIMARY db.printSlaveReplicationInfo()</code></p>

			<p>&nbsp;</p>

			<p><code>source: ds046297-a1.mongolab.com:46297</code></p>

			<p><code>syncedTo: Tue Mar 05 2013 07:48:19 GMT-0800 (PST)</code></p>

			<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<code>= 7475 secs ago (2.08hrs)</code></p>

			<p><code>source: ds046297-a2.mongolab.com:46297</code></p>

			<p><code>syncedTo: Tue Mar 05 2013 07:48:19 GMT-0800 (PST)</code></p>

			<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<code>= 7475 secs ago (2.08hrs)</code></p>
			</td>
		</tr>
	</tbody>
</table>

<p>More than 2 hours &mdash; whoa, isn&rsquo;t that a lot? Maybe!</p>

<p>See, those &ldquo;syncedTo&rdquo; times don&rsquo;t have much to do with the clock on the wall; they&rsquo;re just the timestamp on the last operation that the replica has copied over from the PRIMARY. If the last write operation on the PRIMARY happened 5 minutes ago, then yes: 2 hours is a lot. On the other hand, if the last op was 2.08 hours ago, then this is golden!</p>

<p>To fill in that missing piece of the story, we can use the <code><strong>db.printReplicationInfo()</strong></code> command.</p>

<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	<tbody>
		<tr>
			<td>
			<p><code>rs-ds046297:PRIMARY db.printReplicationInfo()</code></p>

			<p>&nbsp;</p>

			<p><code>configured oplog size:&nbsp;&nbsp; 1024MB</code></p>

			<p><code>log length start to end: 5589secs (1.55hrs)</code></p>

			<p><code>oplog first event time:&nbsp; Tue Mar 05 2013 06:15:19 GMT-0800 (PST)</code></p>

			<p><code>oplog last event time:&nbsp;&nbsp; Tue Mar 05 2013 07:48:19 GMT-0800 (PST)</code></p>

			<p><code>now:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tue Mar 05 2013 09:53:07 GMT-0800 (PST)</code></p>
			</td>
		</tr>
	</tbody>
</table>

<p>Let&rsquo;s see &hellip; PRIMARY&rsquo;s &ldquo;oplog last event time&rdquo; &ndash; SECONDARY&rsquo;s &ldquo;syncedTo&rdquo; = 0.0. Yay.</p>

<p>As fun as that subtraction may be, it&rsquo;s seldom called for. If there is a steady flow of write operations, the last op on the PRIMARY will usually have been quite recent. Thus, a figure like &ldquo;2.08 hours&rdquo; should probably raise eyebrows; you would expect to see a nice low number there instead &mdash; perhaps as high as a few seconds. And, having seen a low number, there would be no need to qualify its context with the second command.</p>

<p>Examine the &ldquo;repl lag&rdquo; graph in MMS</p>

<p>You can also view recent and historical replication lag using the <a href=\"http://www.10gen.com/products/mongodb-monitoring-service\" target=\"_blank\">MongoDB Monitoring Service</a> (MMS) from 10gen. On the Status tab of each SECONDARY node, you&rsquo;ll find the <strong>repl lag</strong> graph:</p>

<p><a href=\"http://blog.mongolab.com/wp-content/uploads/2013/03/Screen-Shot-2013-03-10-at-12.13.22-PM.png\"><img alt=\"Screen Shot 2013-03-10 at 12.13.22 PM\" src=\"http://blog.mongolab.com/wp-content/uploads/2013/03/Screen-Shot-2013-03-10-at-12.13.22-PM.png\" style=\"height:238px; width:396px\" /></a></p>

<h2>How do I monitor for lag?</h2>

<p>It is critical that the replication lag of your replica set(s) be monitored continuously. &nbsp; Since you have to sleep occasionally, this is a job best done by robots. &nbsp;It is essential that these robots be reliable, and that they notify you promptly whenever a replica set is lagging too far behind.</p>

<p>Here are a couple ways you can make sure this is taken care of:</p>

<ul>
	<li>If <a href=\"https://mongolab.com\" target=\"_blank\">MongoLab</a> is hosting your replica set, relax! For any multi-node, highly-available replica set we host for you, you can monitor replication lag in our UI and by default you will receive automated alerts whenever the replication lag exceeds 10 minutes.</li>
	<li>You can also set up an alert using the MMS system. Its&nbsp;<a href=\"http://blog.10gen.com/post/41442945582/announcing-new-mms-alerts\" target=\"_blank\">exciting new features</a> allow you to configure a replication lag alert:</li>
</ul>

<p><a href=\"http://blog.mongolab.com/wp-content/uploads/2013/03/Screen-Shot-2013-03-10-at-1.18.56-PM.png\"><img alt=\"Screen Shot 2013-03-10 at 1.18.56 PM\" src=\"http://blog.mongolab.com/wp-content/uploads/2013/03/Screen-Shot-2013-03-10-at-1.18.56-PM.png\" style=\"height:369px; width:573px\" /></a></p>

<h2>What can I do to minimize lag?</h2>

<p>Out of courtesy (for them or for ourselves), we would like to make those lag-monitoring automata&rsquo;s lives as boring as possible. Here are some tips:</p>

<h3>Tip #1: Make sure your secondary has enough horsepower</h3>

<p>It&rsquo;s not uncommon for people to run under-powered secondaries to save money &mdash; this can be fine if the write load is light. But in scenarios where the write load is heavy, the secondary might not be able to keep up with the primary. To avoid this, you should beef up your secondary so that it&rsquo;s as powerful as your primary.</p>

<p>Specifically, a SECONDARY node should have enough network bandwidth that it can retrieve ops from the PRIMARY&rsquo;s oplog at roughly the rate they&rsquo;re created and also enough storage throughput that it can apply the ops &mdash; i.e., read any affected documents and their index entries into RAM, and commit the altered documents back to disk &mdash; at that same rate. CPU rarely becomes a bottleneck, but it may need to be considered if there are many index keys to compute and insert for the documents that are being added or changed.</p>

<h3>Tip #2: Consider adjusting your write concern</h3>

<p>Your secondary may be lagging simply because your primary&rsquo;s oplog is filling up faster than it can be replicated. Even with an equally-brawny SECONDARY node, the PRIMARY will always be capable of depositing 4MB in its memory-mapped oplog in a fraction of the time those same 4MB will need to make it across a TCP/IP connection.</p>

<p>One viable way to apply some back-pressure to the primary might be to adjust your <a href=\"http://docs.mongodb.org/manual/core/write-operations/#write-concern\" target=\"_blank\">write concern</a>.</p>

<p>If you are currently using a write concern that does not acknowledge writes (aka &ldquo;fire-and-forget&rdquo; mode), you can change your write concern to require an acknowledgement from the primary (<code>w:1</code>) and/or a write to the primary&rsquo;s journal (<code>j:true</code>). Doing so will slow down the rate at which the concerned connection can generate new ops needing replication.</p>

<p>Other times it may be appropriate to use a &lsquo;w&rsquo; &gt; 1 or a &lsquo;w&rsquo; set to &ldquo;<code>majority</code>&rdquo; to ensure that each write to the cluster is replicated to more than one node before the command returns. Requiring confirmation that a write has replicated to secondaries will effectively guarantee that those secondaries have caught up (at least up to the timestamp of this write) before the next command on the same connection can produce more ops in the backlog.</p>

<p>As previously alluded to, choosing the most appropriate write concern for the data durability requirements of your application &mdash; or for particular critical write operations within the application &mdash; is something you must give thought to irrespective of the replication lag issue we&rsquo;re focusing on here. But you should be aware of the interrelationship: just as the durability guarantee of w&gt;1 can be used as a means of forcing a periodic &ldquo;checkpoint&rdquo; on replication, excessive replication lag can show up as a surprisingly high latency (or timeout) for that very occasional critical write operation where you&rsquo;ve used &ldquo;<code>w: majority</code>&rdquo; to make sure it&rsquo;s truly committed.</p>

<p><strong><em>Adjust to taste</em></strong></p>

<p>Having servers acknowledge every write can be a big hit to system throughput. If it makes sense for your application, you can amortize that penalty by doing inserts in batches, requiring acknowledgement only at the end of each batch. The smaller the batch, the greater the back-pressure on PRIMARY data creation rate, and correspondingly greater potential adverse impact to overall throughput.</p>

<p><strong><em>Don&rsquo;t overdo it</em></strong></p>

<p>Using a large value for &lsquo;w&rsquo; can itself be problematic. It represents a demand that <em>w</em> nodes finish working through their existing backlog before the command returns. So, if replication lag is high (in the sense of there being a large volume of data waiting to copy over) when the write command is issued, the command execution time will suffer a proportionally high latency. Also, if enough nodes go offline such that &lsquo;w&rsquo; cannot be satisfied, you have effectively locked up your database. This is basically the opposite of &ldquo;high availability.&rdquo;</p>

<h3>Tip #3: Plan for index builds</h3>

<p>As mentioned earlier, an index build on a secondary is a foreground, blocking operation. If you&rsquo;re going to create an index that is sizeable, perhaps you can arrange to do it during a period of low write activity on the primary. Alternately, if you have more than one secondary, you can follow the steps <a href=\"http://docs.mongodb.org/manual/administration/indexes/#index-building-replica-sets\" target=\"_blank\">here</a> to minimize the impact of building large indexes.</p>

<h3>Tip #4: Take backups without blocking</h3>

<p>Earlier we discussed the technique of locking the secondary to do a backup. There are other alternatives to consider here, including filesystem snapshots and &ldquo;point-in-time&rdquo; backups using <a href=\"http://docs.mongodb.org/manual/reference/mongodump/#cmdoption-mongodump--oplog\" target=\"_blank\">the &ldquo;<tt>--oplog</tt>&rdquo; option of <tt>mongodump</tt></a> without locking. These are preferable to locking the secondary during a period of active writes if there&rsquo;s any chance you&rsquo;ll use the secondary for anything other than backups.</p>

<h3>Tip #5: Be sure capped collections have an <tt>_id</tt> field &amp; a unique index</h3>

<p>Reliable replication is not possible unless there is a unique index on the <code>_id</code> field. Before <a href=\"http://docs.mongodb.org/manual/release-notes/2.2/#id-indexes-capped-collections\" target=\"_blank\">MongoDB version 2.2</a>, <a href=\"http://docs.mongodb.org/manual/core/capped-collections/\" target=\"_blank\">capped collections</a> did not have an <code>_id</code> field or index by default. If you have a collection like this, you should create an index on the <code>_id</code> field, specifying <code>unique: true</code>. Failing to do this can, in certain situations, cause replication to <strong>halt entirely</strong>. So &hellip; this should not be regarded as optional.</p>

<h3>Tip #6: Check for replication errors</h3>

<p>If you see that replication lag is only increasing (and never falling), your replica set could be experiencing replication errors. To check for errors, run <code>rs.status()</code> and look at the <code>errmsg</code> field in the result. Additionally, check the log file of your secondary and look for error messages there.</p>

<p>One specific example: if you see &ldquo;<tt>RS102 too stale to catch up</tt>&rdquo; in the secondary&rsquo;s <tt>mongodb.log</tt> or in the <code>errmsg</code> field when running <code>rs.status()</code>, it means that secondary has fallen so far behind that there is not enough history retained by the primary (its &ldquo;oplog size&rdquo;) to bring it up to date. In this case, your secondary will require a full resynchronization from the primary.</p>

<p>In general, though, what you do in response to an error depends on the error. Sometimes you can simply restart the <tt>mongod</tt> process for your secondary; but the majority of the time you will need to understand the root cause of the error before you can fix the problem.</p>

<h2>Don&rsquo;t let replication lag take you by surprise.</h2>

<p>At the end of the day, replication lag is just one more source of risk in any high-availability system that we need to understand and design around. Striking the right balance between performance and &ldquo;safety&rdquo; of write operations is an exercise in risk management &mdash; the &ldquo;right&rdquo; balance will be different in different situations. For an application on a tight budget with occasional spikes in write volume, for example, you might decide that a large replication lag in the wake of those spikes is acceptable given the goals of the application, and so an underpowered secondary makes sense. At the opposite extreme, for an application where every write is precious and sacred, the required &ldquo;majority&rdquo; write concern will mean you have essentially no tolerance for replication lag above the very minimum possible.&nbsp;&nbsp;The good news is that MongoDB makes this all very configurable, even on an operation by operation basis.</p>

<p>We hope this article has given you some insight into the phenomenon of replication lag that will enable you to reason about the risk it poses for a high-availability MongoDB application, and armed you with some tools for managing it. As always, <a href=\"mailto:support@mongolab.com\" target=\"_blank\">let us know if we can help</a>!</p>

<p><a href=\"http://blog.mongolab.com/feed/\"><img alt=\"\" src=\"http://blog.mongolab.com/wp-content/themes/canvas/images/ico-social-rss.png\" /></a></p>

<p>Related Posts:</p>

<ul>
	<li>No related posts found</li>
</ul>

<p><a href=\"http://blog.mongolab.com/2013/03/object-modeling-in-node-js-with-mongoose/\">&larr; Object Modeling in Node.js with Mongoose</a></p>

<p><a href=\"http://blog.mongolab.com/2013/03/mongolab-at-overdriver-com/\">MongoLab at Overdriver.com &rarr;</a></p>

<h3>About</h3>

<p><a href=\"http://mongolab.com/\"><img alt=\"MongoLab: MongoDB-as-a-Service | Cloud Hosted MongoDB\" src=\"http://blog.mongolab.com/wp-content/uploads/2012/04/MongoLab-logo_white-horizontal-200px.png\" /></a></p>

<p>This blog is brought to you by <a href=\"https://mongolab.com\">MongoLab</a>, the fully-managed MongoDB Database-as-a-Service (DBaaS) platform that automates the operational aspects of running MongoDB in the cloud.</p>

<p>We hope you enjoy it!</p>

<h3>Search</h3>

<p>&nbsp;</p>

<h3>Recent Posts</h3>

<ul>
	<li><a href=\"http://blog.mongolab.com/2013/03/sensor-data-arduino-mongodb/\">Weekend Project: Send sensor data from Arduino to MongoDB</a></li>
	<li><a href=\"http://blog.mongolab.com/2013/03/mongolab-at-overdriver-com/\">MongoLab at Overdriver.com</a></li>
	<li><a href=\"http://blog.mongolab.com/2013/03/replication-lag-the-facts-of-life/\">Replication Lag &amp; The Facts of Life</a></li>
	<li><a href=\"http://blog.mongolab.com/2013/03/object-modeling-in-node-js-with-mongoose/\">Object Modeling in Node.js with Mongoose</a></li>
	<li><a href=\"http://blog.mongolab.com/2013/02/mongolab-at-pycon-us-2013/\">MongoLab at PyCon US 2013</a></li>
</ul>

<h3>Recent Tweets</h3>

<ul>
	<li>@<a href=\"http://twitter.com/flarb\">flarb</a> We do MongoDB-as-a-Service on AWS (and other clouds). Give us a try! <a href=\"http://twitter.com/mongolab/statuses/318069187748716544\">about 12 hours ago</a></li>
	<li>Get your weekend hack on! Check out our easy how-to on sending sensor data from @<a href=\"http://twitter.com/arduino\">arduino</a> to #mongodb: <a href=\"http://t.co/e8H3aV2Bnq\">http://t.co/e8H3aV2Bnq</a> <a href=\"http://twitter.com/mongolab/statuses/317719998007230464\">1 day ago</a></li>
	<li>@<a href=\"http://twitter.com/elotente\">elotente</a> That&rsquo;s not we like to hear. No known issues currently. We&rsquo;d be happy to help at support@mongolab.com. ^jdc <a href=\"http://twitter.com/mongolab/statuses/317376451324280832\">2 days ago</a></li>
	<li>RT @<a href=\"http://twitter.com/joyent\">joyent</a>: #MongoDB + #Node.js runs swell! How about making it super easy by running #mongolab on #joyent, how-to video: <a href=\"http://t.co/fL\">http://t.co/fL</a> ... <a href=\"http://twitter.com/mongolab/statuses/317053888333217792\">3 days ago</a></li>
	<li>@<a href=\"http://twitter.com/Joe_Wegner\">Joe_Wegner</a> thanks Joe; we appreciate your note! <a href=\"http://twitter.com/mongolab/statuses/316919323874689025\">3 days ago</a></li>
</ul>

<p>Follow <a href=\"http://twitter.com/mongolab\"><strong>@mongolab</strong></a> on Twitter</p>

<p>&#39;sd;g</p>

<p>s;d</p>

<p>g;s;&#39;ss</p>

<p>bkdhsdlsdkdj&nbsp; a adgsdgsdgsdskd;gskjgksdjgksdjgpsddgpsdjgsdjggsj</p>

<p>\\</p>

<p>ksds&#39;ljgljslsgsdkd jsddsgksdgjsddgjddggsd&nbsp;&nbsp;&nbsp; ksdjgkdjggdljggk</p>

<p>&nbsp;kssd</p>

<p>&nbsp;</p>

<p>&nbsp;&nbsp;&nbsp; s</p>

<p>dgsdd</p>

<p>g</p>

<p>sddg&#39;s;kss;gsdgds</p>

<p>&#39;gksddkpjggjgjsdjgss</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>jldl</p>

<p>gg</p>
