<?php

defined('ABSPATH') or die("you do not have acces to this page!");

if (!class_exists("cmplz_document_core")) {
    class cmplz_document_core
    {
        private static $_this;

        function __construct()
        {
            if (isset(self::$_this))
                wp_die(sprintf(__('%s is a singleton class and you cannot create a second instance.', 'complianz'), get_class($this)));

            self::$_this = $this;


        }

        static function this()
        {

            return self::$_this;
        }


        public function is_public_page($type)
        {
            if (!isset(COMPLIANZ()->config->pages[$type])) return false;

            if (isset(COMPLIANZ()->config->pages[$type]['public']) && COMPLIANZ()->config->pages[$type]['public']) {
                return true;
            }
            return false;
        }

        public function revoke_link($atts = [], $content = null, $tag = '')
        {

            // normalize attribute keys, lowercase
            $atts = array_change_key_case((array)$atts, CASE_LOWER);

            ob_start();

            // override default attributes with user attributes
            $atts = shortcode_atts(['text' => false,], $atts, $tag);

            echo cmplz_revoke_link($atts['text']);

            return ob_get_clean();

        }


        /*
         * Check if a page is required. If no condition is set, return true.
         *
         * */

        public function page_required($page)
        {
            if (!isset($page['condition'])) return true;

            if (isset($page['condition'])) {
                $conditions = $page['condition'];
                $condition_answer = reset($conditions);
                $condition_question = key($conditions);
                if (COMPLIANZ()->field->get_value($condition_question) == $condition_answer) {
                    return true;
                }
            }

            return false;

        }


        public function insert_element($element, $post_id)
        {

            if (isset($element['condition'])) {
                $fields = COMPLIANZ()->config->fields();

                foreach ($element['condition'] as $question => $condition_answer) {
                    if ($condition_answer == 'loop') continue;
                    if (!isset($fields[$question]['type'])) return false;

                    $type = $fields[$question]['type'];
                    $value = cmplz_get_value($question, $post_id);

                    if ($type == 'multicheckbox') {
                        if (!isset($value[$condition_answer]) || !$value[$condition_answer]) {
                            return false;
                        }
                    } else {
                        if ($value != $condition_answer) {
                            return false;
                        }
                    }

                }

            }

            if (isset($element['callback_condition'])) {
                $func = $element['callback_condition'];
                $show_field = $func();
                if (!$show_field) return false;
            }
            return true;
        }


        /*
         * Check if this element should loop through dynamic multiple values
         *
         *
         * */

        public function is_loop_element($element)
        {
            if (isset($element['condition'])) {
                foreach ($element['condition'] as $question => $condition_answer) {
                    if ($condition_answer == 'loop') return true;
                }
            }

            return false;
        }


        public function get_document_html($type, $post_id = false)
        {
            if (!isset(COMPLIANZ()->config->document_elements[$type])) return "";

            $elements = COMPLIANZ()->config->document_elements[$type];

            $html = "";
            $paragraph = 0;
            $sub_paragraph = 0;
            $annex = 0;
            $annex_arr = array();
            $paragraph_id_arr = array();
            foreach ($elements as $id => $element) {
                //count paragraphs
                if ($this->insert_element($element, $post_id) || $this->is_loop_element($element)) {

                    if (isset($element['title']) && (!isset($element['numbering']) || $element['numbering'])) {
                        $sub_paragraph = 0;
                        $paragraph++;
                        $paragraph_id_arr[$id]['main'] = $paragraph;
                    }

                    //count subparagraphs
                    if (isset($element['subtitle']) && $paragraph > 0 && (!isset($element['numbering']) || $element['numbering'])) {
                        $sub_paragraph++;
                        $paragraph_id_arr[$id]['main'] = $paragraph;
                        $paragraph_id_arr[$id]['sub'] = $sub_paragraph;
                    }

                    //count annexes
                    if (isset($element['annex'])) {
                        $annex++;
                        $annex_arr[$id] = $annex;
                    }
                }

                if ($this->is_loop_element($element) && $this->insert_element($element, $post_id)) {
                    $fieldname = key($element['condition']);
                    $values = cmplz_get_value($fieldname, $post_id);
                    $loop_content = '';
                    if (!empty($values)) {
                        foreach ($values as $value) {
                            //line specific for cookies, to hide or show conditionally
                            if (isset($value['show']) && $value['show'] !== 'on') continue;

                            if (!is_array($value)) $value = array($value);
                            $fieldnames = array_keys($value);
                            if (count($fieldnames) == 1 && $fieldnames[0] == 'key') continue;

                            $loop_section = $element['content'];
                            foreach ($fieldnames as $fieldname) {

                                $field_value = (isset($value[$fieldname])) ? $value[$fieldname] : '';

                                if (!empty($field_value) && is_array($field_value)) $field_value = implode(', ', $field_value);
                                $loop_section = str_replace('[' . $fieldname . ']', $field_value, $loop_section);
                            }

                            $loop_content .= $loop_section;

                        }
                        $html .= $this->wrap_header($element, $paragraph, $sub_paragraph, $annex);
                        $html .= $this->wrap_content($loop_content);
                    }
                } elseif ($id === 'wp_privacy_policies') {

                    $policies = $this->get_wp_privacy_policy_data();
                    $stored_policies = cmplz_get_value('wp_privacy_policies');
                    $policy_html = "";
                    $added_by_user = false;
                    foreach ($policies as $policy) {
                        if (isset($policy['removed'])) continue;
                        $s_plugin_name = sanitize_title($policy['plugin_name']);
                        $added_by_user = (isset($stored_policies[$s_plugin_name]) && $stored_policies[$s_plugin_name] == 'on') ? true : false;
                        if ($added_by_user) {
                            $policy_html .= $this->wrap_sub_header($policy['plugin_name'], $paragraph, $sub_paragraph);
                            $policy_html .= $this->wrap_content($policy['policy_text']);
                        }
                    }

                    //if at least one wp policy is added, added by user is true, so add the header as well.
                    if ($added_by_user) {
                        $html .= $this->wrap_header($element, $paragraph, $sub_paragraph, $annex);
                        $html .= $this->wrap_content($element['content']);
                        $html .= $policy_html;
                    }

                } elseif ($this->insert_element($element, $post_id)) {
                    $html .= $this->wrap_header($element, $paragraph, $sub_paragraph, $annex);
                    if (isset($element['content'])) {
                        $list = (isset($element['list']) && $element['list']) ? true : false;
                        $html .= $this->wrap_content($element['content'], $list);
                    }
                }
            }

            $html = $this->replace_fields($html, $paragraph_id_arr, $annex_arr, $post_id);

            return '<div id="cmplz-document">' . $html . '</div>';
        }

        public function wrap_header($element, $paragraph, $sub_paragraph, $annex)
        {
            $nr = "";
            if (isset($element['annex'])) {
                $nr = __("Annex", 'complianz') . " " . $annex . ": ";
                if (isset($element['title'])) {
                    return '<h3 class="annex">' . cmplz_esc_html($nr) . cmplz_esc_html($element['title']) . '</h3>';
                }
                if (isset($element['subtitle'])) {
                    return '<h4 class="annex">' . cmplz_esc_html($nr) . cmplz_esc_html($element['subtitle']) . '</h4>';
                }
            }

            if (isset($element['title'])) {
                if (empty($element['title'])) return "";
                if ($paragraph > 0 && $this->is_numbered_element($element)) $nr = $paragraph;
                return '<h3>' . cmplz_esc_html($nr) . ' ' . cmplz_esc_html($element['title']) . '</h3>';
            }

            if (isset($element['subtitle'])) {
                if ($paragraph > 0 && $sub_paragraph > 0 && $this->is_numbered_element($element)) $nr = $paragraph . "." . $sub_paragraph . " ";
                return '<h4>' . cmplz_esc_html($nr) . cmplz_esc_html($element['subtitle']) . '</h4>';
            }
        }


        /*
         * Check if this element should be numbered
         * if no key is set, default is true
         *
         *
         * */

        public function is_numbered_element($element)
        {

            if (!isset($element['numbering'])) return true;

            return $element['numbering'];
        }

        public function wrap_sub_header($header, $paragraph, $subparagraph)
        {
            if (empty($header)) return "";
            return '<h4>' . cmplz_esc_html($header) . '</h4>';
        }

        public function wrap_content($content, $list = false)
        {
            if (empty($content)) return "";
            if ($list) {
                return '<span>' . $content . '</span><br>';
            }
            return '<p>' . $content . '</p>';
        }

        /*
         * Replace all fields in the resulting output
         *
         *
         * */

        private function replace_fields($html, $paragraph_id_arr, $annex_arr, $post_id)
        {
            //replace references
            foreach ($paragraph_id_arr as $id => $paragraph) {
                $html = str_replace("[article-$id]", sprintf(__('(See paragraph %s)', 'complianz'), cmplz_esc_html($paragraph['main'])), $html);
            }

            foreach ($annex_arr as $id => $annex) {
                $html = str_replace("[annex-$id]", sprintf(__('(See annex %s)', 'complianz'), cmplz_esc_html($annex)), $html);
            }

            //some custom elements
            $html = str_replace("[cookie_accept_text]", cmplz_get_value('accept'), $html);
            $html = str_replace("[domain]", cmplz_esc_url_raw(get_site_url()), $html);
            $html = str_replace("[cookie_policy_url]", cmplz_esc_url_raw(COMPLIANZ()->cookie->get_cookie_statement_page()), $html);

            $date = $post_id ? get_the_date('', $post_id) : date(cmplz_get_option('date_format'), time());
            $date = cmplz_localize_date($date);
            $html = str_replace("[publish_date]", cmplz_esc_html($date), $html);

            //replace all fields.
            foreach (COMPLIANZ()->config->fields() as $fieldname => $field) {
                if (strpos($html, "[$fieldname]") !== FALSE) {
                    $html = str_replace("[$fieldname]", $this->get_plain_text_value($fieldname, $post_id), $html);
                    //when there's a closing shortcode it's always a link
                    $html = str_replace("[/$fieldname]", "</a>", $html);
                }

                if (strpos($html, "[comma_$fieldname]") !== FALSE) {
                    $html = str_replace("[comma_$fieldname]", $this->get_plain_text_value($fieldname, $post_id, false), $html);
                }
            }

            return $html;

        }


        private function get_plain_text_value($fieldname, $post_id, $list_style = true)
        {
            $value = cmplz_get_value($fieldname, $post_id);

            $front_end_label = isset(COMPLIANZ()->config->fields[$fieldname]['front-end-label']) ? COMPLIANZ()->config->fields[$fieldname]['front-end-label'] : '';

            if (COMPLIANZ()->config->fields[$fieldname]['type'] == 'url') {
                $value = '<a href="' . $value . '" target="_blank">';
            } elseif (COMPLIANZ()->config->fields[$fieldname]['type'] == 'radio') {
                $options = COMPLIANZ()->config->fields[$fieldname]['options'];
                $value = isset($options[$value]) ? $options[$value] : '';
            } elseif (is_array($value)) {
                $options = COMPLIANZ()->config->fields[$fieldname]['options'];
                //array('3' => 1 );
                $value = array_filter($value, function ($item) {
                    return $item == 1;
                });
                $value = array_keys($value);
                //array (1, 4, 6)
                $labels = "";
                foreach ($value as $index) {
                    if ($list_style)
                        $labels .= "<li>" . cmplz_esc_html($options[$index]) . '</li>';
                    else
                        $labels .= $options[$index] . ', ';
                }

                if ($list_style) {
                    $labels = "<ul>" . $labels . "</ul>";
                } else {
                    $labels = cmplz_esc_html(rtrim($labels, ', '));
                    $labels = strrev(implode(strrev(', ' . __('and', 'complianz')), explode(strrev(','), strrev($labels), 2)));
                }

                $value = $labels;
            } else {
                if (isset(COMPLIANZ()->config->fields[$fieldname]['options'])) {
                    $options = COMPLIANZ()->config->fields[$fieldname]['options'];
                    if (isset($options[$value])) $value = $options[$value];
                }
            }

            if (!empty($value)) $value = $front_end_label . $value;
            return $value;
        }

    }
} //class closure
