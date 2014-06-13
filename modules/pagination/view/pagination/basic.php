<p class="pagination">

	<?php if ($first_page !== FALSE): ?>
		<a href="/" rel="first"><?php echo 'Pierwsza'; ?></a>
	<?php else: ?>
		<?php echo 'Pierwsza'; ?>
	<?php endif ?>

	<?php if ($previous_page !== FALSE): ?>
		<a href="<?php echo URL::site("?page=".$previous_page) ?>" rel="prev"><?php echo 'Poprzednia'; ?></a>
	<?php else: ?>
		<?php echo 'Poprzednia'; ?>
	<?php endif ?>

	<?php for ($i = 1; $i <= $total_pages; $i++): ?>

		<?php if ($i == $current_page): ?>
			<strong><?php echo $i ?></strong>
		<?php else: ?>
			<a href="<?php echo URL::site("?page=".$i) ?>"><?php echo $i ?></a>
		<?php endif ?>

	<?php endfor ?>

	<?php if ($next_page !== FALSE): ?>
		<a href="<?php echo URL::site("?page=".$next_page) ?>" rel="next"><?php echo 'Następna'; ?></a>
	<?php else: ?>
		<?php echo 'Następna'; ?>
	<?php endif ?>

	<?php if ($last_page !== FALSE): ?>
		<a href="<?php echo URL::site("?page=".$last_page) ?>" rel="last"><?php echo 'Ostatnia' ?></a>
	<?php else: ?>
		<?php echo 'Ostatnia'; ?>
	<?php endif ?>

</p><!-- .pagination -->