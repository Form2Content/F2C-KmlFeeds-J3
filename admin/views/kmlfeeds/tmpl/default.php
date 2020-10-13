<?php 
defined('JPATH_PLATFORM') or die('Restricted acccess');

require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'viewhelper.form2contentkmlfeeds.php');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::stylesheet('com_form2contentkmlfeeds/style.css', array(), true);

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$f2cConfig	= F2cFactory::getConfig();
$dateFormat = str_replace('%', '', $f2cConfig->get('date_format'));
$sortFields = $this->getSortFields();
$saveOrder	= false;
?>
<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_form2contentkmlfeeds&view=kmlfeeds');?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="kmlfeeds_filter_search" class="element-invisible"><?php echo JText::_('COM_FORM2CONTENTKMLFEEDS_KMLFEED_FILTER_SEARCH_DESC');?></label>
				<input type="text" name="contenttypes_filter_search" placeholder="<?php echo JText::_('COM_FORM2CONTENTKMLFEEDS_KMLFEED_FILTER_SEARCH_DESC'); ?>" id="kmlfeeds_filter_search" value="<?php echo $this->escape($this->state->get('kmlfeeds.filter.search')); ?>" title="<?php echo JText::_('COM_FORM2CONTENTKMLFEEDS_KMLFEED_FILTER_SEARCH_DESC'); ?>" />
			</div>
			<div class="btn-group pull-left hidden-phone">
				<button class="btn tip" type="submit" rel="tooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn tip" type="button" onclick="document.id('kmlfeeds_filter_search').value='';this.form.submit();" rel="tooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>
			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div>
		</div>
		<div class="clearfix"> </div>
		<table class="table table-striped" id="kmlFeedsList">
			<thead>
				<tr>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th width="1%" style="min-width:55px" class="nowrap center">
						<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap hidden-phone">
						<?php echo JText::_('COM_FORM2CONTENTKMLFEEDS_FILTER'); ?>
					</th>
					<th>
						<?php echo JText::_('COM_FORM2CONTENTKMLFEEDS_URL'); ?>
					</th>
					<th width="10%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort',  'COM_FORM2CONTENTKMLFEEDS_DATE_CREATED', 'a.created', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort',  'COM_FORM2CONTENTKMLFEEDS_DATE_MODIFIED', 'a.modified', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="8">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$item->max_ordering = 0; //??
				$canChange = true;
				$ordering   		= ($listOrder == 'a.ordering');
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td class="center">
						<div class="btn-group">
							<?php echo JHtml::_('jgrid.published', $item->published, $i, 'kmlfeeds.', $canChange, 'cb'); ?>
						</div>
					</td>
					<td class="nowrap">
						<a href="<?php echo JRoute::_('index.php?option=com_form2contentkmlfeeds&task=kmlfeed.edit&id=' . $item->id);?>" title="<?php echo JText::_('JACTION_EDIT');?>">
							<?php echo $this->escape($item->title); ?>
						</a>
					</td>
					<td class="small hidden-phone">
						<a href="<?php echo JRoute::_('index.php?option=com_form2contentkmlfeeds&view=kmlfeedfilters&feed_id='. $item->id); ?>">
							<i class="icon-cog f2cicon-large" title="<?php echo JText::_('COM_FORM2CONTENTKMLFEEDS_FILTERS', true); ?>"></i>
						</a>
					</td>
					<td class="small hidden-phone">
						<a href="<?php echo $this->escape($item->cacheUrl); ?>" target="_blank"><?php echo $this->escape($item->cacheUrl); ?></a>
						 (<?php echo JText::_('COM_FORM2CONTENTKMLFEEDS_REGULAR_RENDERING'); ?>)
						<br/>
						<a href="<?php echo $this->escape($item->renderUrl); ?>" target="_blank"><?php echo $this->escape($item->renderUrl); ?></a>
						 (<?php echo JText::_('COM_FORM2CONTENTKMLFEEDS_FORCED_RENDERING'); ?>)
					</td>
					<td class="nowrap small hidden-phone">
						<?php echo JHtml::_('date', $item->created, $dateFormat); ?>
					</td>
					<td class="nowrap small hidden-phone">
						<?php
						if($item->modified && ($item->modified != $this->nullDate))
						{
							echo JHtml::_('date',$item->modified, $dateFormat);
						} 
						?>
					</td>
					<td class="center hidden-phone">
						<?php echo (int) $item->id; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>		
		</table>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
		<?php echo DisplayCredits(); ?>
	</div>	
</form>