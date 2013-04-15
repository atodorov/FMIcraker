<h2>Requirements</h2>

<p>For a three-member replica set you need two instances in a primary facility (hereafter, &ldquo;Site A&rdquo;) and one member in a secondary facility (hereafter, &ldquo;Site B&rdquo;.) Site A should be the same facility or very close to your primary application infrastructure (i.e. application servers, caching layer, users, etc.)</p>

<p>For a four-member replica set you need two members in Site A, two members in Site B (or one member in Site B and one member in Site C,) and a single <a href=\"http://docs.mongodb.org/manual/reference/glossary/#term-arbiter\"><em>arbiter</em></a> in Site A.</p>

<p>For replica sets with additional members in the secondary facility or with multiple secondary facilities, the requirements are the same as above but with the following notes:</p>

<ul>
	<li>Ensure that a majority of the <a href=\"http://docs.mongodb.org/manual/administration/replica-sets/#replica-set-non-voting-members\"><em>voting members</em></a> are within Site A. This includes <a href=\"http://docs.mongodb.org/manual/administration/replica-sets/#replica-set-secondary-only-members\"><em>secondary-only members</em></a> and <a href=\"http://docs.mongodb.org/manual/administration/replica-sets/#replica-set-arbiters\"><em>arbiters</em></a> For more information on the need to keep the voting majority on one site, see :ref`replica-set-elections-and-network-partitions`.</li>
	<li>If you deploy a replica set with an uneven number of members, deploy an <a href=\"http://docs.mongodb.org/manual/administration/replica-sets/#replica-set-arbiters\"><em>arbiter</em></a> on Site A. The arbiter must be on site A to keep the majority there.</li>
</ul>

<p>For all configurations in this tutorial, deploy each replica set member on a separate system. Although you may deploy more than one replica set member on a single system, doing so reduces the redundancy and capacity of the replica set. Such deployments are typically for testing purposes and beyond the scope of this tutorial.</p>
