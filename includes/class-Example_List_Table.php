<?php

class Example_List_Table extends WP_List_Table
{

    function __construct()
    {
        parent::__construct(array(
            'singular' => 'log',
            'plural' => 'logs',
            'ajax' => true,
        ));


        $this->prepare_items();
    }

    function prepare_items()
    {
        $this->set_pagination_args(array(
            'total_items' => wp_count_posts($type = 'stripe_payment', $perm = '')->publish,
            'per_page' => $per_page,
        ));
        $cur_page = (int)$this->get_pagenum();
        $this->items = get_posts(array(
            'numberposts' => -1,
            'post_type' => 'stripe_payment'
        ));
        $this->bulk_action_handler();

    }

    function get_columns()
    {
        return array(
            'cb' => '<input type="checkbox" />',
            'card_phone_number' => 'Card/Phone Number',
            'sum' => 'Sum',
            'id' => 'ID',
            'system' => 'Payment System',
            'token' => 'Token/Email',
            'status' => 'Payment Status',
            'test' => 'Refresh item'
        );
    }

    function get_sortable_columns()
    {
        return array(
            'cardNumber' => array('cardNumber', 'ASC'),
        );
    }

    function extra_tablenav($which)
    {
        echo '<div class="alignleft actions"></div>';
    }

    protected function get_bulk_actions()
    {
        return array(
            'delete' => 'Delete',
        );
    }

    function column_default($item, $colname)
    {
        $meta_value = get_post_meta($item->id, $colname, true);
        if ($colname) {
            if ($colname === 'name') {
                $actions = array();
                $actions['edit'] = sprintf('<a href="%s">%s</a>', 'post=' . $item->id . '&action=edit', __('edit', 'hb-users'));
                return esc_html($meta_value) . $this->row_actions($actions);
            }
            if ($colname === 'test' && get_post_meta($item->id, 'system', true) === 'paypal') {
                echo '<input type="button" id="refresh_status" value="Refresh"></input>';
            }
            return esc_html($meta_value);
        } else {
            return isset($item->$colname) ? $item->$colname : print_r($item, 1);
        }
    }

    function column_cb($item)
    {
        echo '<input type="checkbox" name="licids[]" id="cb-select-' . $item->id . '" value="' . $item->id . '" />';
    }

    private function bulk_action_handler()
    {
        if (empty($_POST['licids']) || empty($_POST['_wpnonce'])) return;

        if (!$action = $this->current_action()) return;

        if (!wp_verify_nonce($_POST['_wpnonce'], 'bulk-' . $this->_args['plural']))
            wp_die('nonce error');
        if ($action === 'delete') {
            foreach ($_POST['licids'] as $val) {
                wp_delete_post($val);
            }
            header("Refresh:0");
        }
        return;
    }
}

