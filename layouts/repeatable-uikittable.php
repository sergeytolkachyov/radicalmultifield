<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

/**
 * Make thing clear
 *
 * @var JForm   $tmpl             The Empty form for template
 * @var array   $forms            Array of JForm instances for render the rows
 * @var bool    $multiple         The multiple state for the form field
 * @var int     $min              Count of minimum repeating in multiple mode
 * @var int     $max              Count of maximum repeating in multiple mode
 * @var string  $fieldname        The field name
 * @var string  $control          The forms control
 * @var string  $label            The field label
 * @var string  $description      The field description
 * @var array   $buttons          Array of the buttons that will be rendered
 * @var bool    $groupByFieldset  Whether group the subform fields by it`s fieldset
 */
extract($displayData);

// Add script
if ($multiple)
{
    $wa = Factory::getApplication()->getDocument()->getWebAssetManager();
	$wa->useScript('jquery')
    ->registerAndUseScript('radicalmultifield.subform.repeatable.js','system/subform-repeatable.js', ['version' => 'auto', 'relative' => true]);
}

// Build heading
$table_head = '';

if (!empty($groupByFieldset))
{
	foreach ($tmpl->getFieldsets() as $fieldset) {
		$table_head .= '<th>' . Text::_($fieldset->label);

		if (!empty($fieldset->description))
		{
			$table_head .= '<br /><small style="font-weight:normal">' . Text::_($fieldset->description) . '</small>';
		}

		$table_head .= '</th>';
	}

	$sublayout = 'section-byfieldsets';
}
else
{
	foreach ($tmpl->getGroup('') as $field) {
		$table_head .= '<th>' . strip_tags($field->label);
		$table_head .= '<br /><small style="font-weight:normal">' . Text::_($field->description) . '</small>';
		$table_head .= '</th>';
	}

	$sublayout = 'section';

	// Label will not be shown for sections layout, so reset the margin left
	$wa->addInlineStyle(
		'.subform-table-sublayout-section .controls { margin-left: 0px }'
	);
}
?>
<div class="row-fluid">
	<div class="subform-repeatable-wrapper subform-table-layout uk-card uk-card-default uk-padding-small subform-table-sublayout-<?php echo $sublayout; ?>">
		<div
            class="subform-repeatable"
            data-bt-add="a.group-add-<?php echo $unique_subform_id; ?>"
            data-bt-remove="a.group-remove-<?php echo $unique_subform_id; ?>"
            data-bt-move="a.group-move-<?php echo $unique_subform_id; ?>"
            data-repeatable-element="tr.subform-repeatable-group-<?php echo $unique_subform_id; ?>"
            data-rows-container="tbody.rows-container-<?php echo $unique_subform_id; ?>"
            data-minimum="<?php echo $min; ?>" data-maximum="<?php echo $max; ?>"
        >

		<table class="adminlist uk-table uk-table-striped uk-table-middle uk-position-relative">
			<thead>
				<tr>
					<?php echo $table_head; ?>
                    <th style="width: 18%">
	                    <?php if (!empty($buttons)) : ?>
                            <?php if (!empty($buttons['add'])) : ?>
                                <a class="group-add-<?php echo $unique_subform_id; ?> btn button uk-button-text uk-button-small" aria-label="<?php echo Text::_('JGLOBAL_FIELD_ADD'); ?>"><span uk-icon="icon: plus"></span> <?php echo Text::_('JGLOBAL_FIELD_ADD'); ?></a>
		                    <?php endif;?>
                        <?php endif;?>
                    </th>
				</tr>
			</thead>
			<tbody class="rows-container-<?php echo $unique_subform_id; ?>">
            <?php foreach ($forms as $k => $form):
                echo $this->sublayout(
                    $sublayout,
                    array(
                        'form' => $form,
                        'basegroup' => $fieldname,
                        'group' => $fieldname . $k,
                        'buttons' => $buttons,
                        'unique_subform_id' => $unique_subform_id,
                    )
                );
            endforeach; ?>
			</tbody>
		</table>

        <?php if (!empty($buttons)) : ?>
            <?php if (!empty($buttons['add'])) : ?>
                <div class="uk-text-center uk-margin-bottom">
                    <a class="group-add-<?php echo $unique_subform_id; ?> button uk-button uk-button-text uk-button-small" aria-label="<?php echo Text::_('JGLOBAL_FIELD_ADD'); ?>"><span uk-icon="icon: plus"></span> <?= Text::_('JGLOBAL_FIELD_ADD') ?></a>
                </div>
            <?php endif; ?>
        <?php endif; ?>

            <?php if ($multiple) : ?>
                <template class="subform-repeatable-template-section"><?php echo trim(
                        $this->sublayout(
                            $sublayout,
                            array(
                                'form' => $tmpl,
                                'basegroup' => $fieldname,
                                'group' => $fieldname . 'X',
                                'buttons' => $buttons,
                                'unique_subform_id' => $unique_subform_id,
                            )
                        )
                    ); ?></template>
            <?php endif; ?>
		</div>
	</div>
</div>
