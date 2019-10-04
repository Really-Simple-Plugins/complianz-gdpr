<?php

defined('ABSPATH') or die("you do not have acces to this page!");

if (!class_exists("cmplz_document_core")) {
    class cmplz_document_core
    {
        private static $_this;

        function __construct()
        {
            if (isset(self::$_this))
                wp_die(sprintf(__('%s is a singleton class and you cannot create a second instance.', 'complianz-gdpr'), get_class($this)));

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

        /*
         * period in seconds
         *
         * */
        public function not_updated_in($period){
            //if the wizard is never completed, we don't want any update warnings.
            if (!get_option('cmplz_wizard_completed_once')) {
                return false;
            }

            $date = get_option('cmplz_documents_update_date');
            if (!$date) return false;

            $time_passed =  time() - $date;
            if ($time_passed>$period) return true;

            return false;
        }

        /*
         * Check if a page is required. If no condition is set, return true.
         * condition is "AND", all conditions need to be met.
         * */

        public function page_required($page)
        {
            if (!is_array($page)) {
                if (!isset(COMPLIANZ()->config->pages[$page])) return false;

                $page = COMPLIANZ()->config->pages[$page];
            }

            //if it's not public, it's not required
            if (isset($page['public']) && $page['public']==false) return false;

            //if there's no condition, we set it as required
            if (!isset($page['condition'])) return true;

            if (isset($page['condition'])) {
                $conditions = $page['condition'];
                $condition_met = true;
                foreach ($conditions as $condition_question => $condition_answer){

                    $value = cmplz_get_value($condition_question, false, false, $use_default=false);
                    $invert = false;
                    if (strpos($condition_answer, 'NOT ')!==FALSE) {
                        $condition_answer = str_replace('NOT ', '', $condition_answer);
                        $invert = true;
                    }

                    if (is_array($value)) {

                        if (!isset($value[$condition_answer]) || !$value[$condition_answer]) {
                            $condition_met = false;
                        } else {
                            $condition_met =true;
                        }

                    } else {
                        $condition_met = ($value == $condition_answer);
                    }

                    //if one condition is not met, we break with this condition, so it will return false.
                    if (!$condition_met) {
                        break;
                    }

                }

                $condition_met = $invert ? !$condition_met : $condition_met;

                return $condition_met;
            }
            return false;

        }


        /*
         * Check if an element should be inserted. AND implementation s
         *
         *
         * */

        public function insert_element($element, $post_id)
        {

            if ($this->callback_condition_applies($element) && $this->condition_applies($element, $post_id)) return true;

            return false;

        }

        /**
         * @param $element
         * @return bool
         */

        public function callback_condition_applies($element){

            if (isset($element['callback_condition'])) {
                $conditions = is_array($element['callback_condition']) ?  $element['callback_condition'] : array($element['callback_condition']);
                foreach ($conditions as $func) {
                    $invert = false;
                    if (strpos($func, 'NOT ') !== FALSE) {
                        $invert = true;
                        $func = str_replace('NOT ', '', $func);
                    }

                    if (!function_exists($func)) break;

                    $show_field = $func();
                    if ($invert) $show_field = !$show_field;
                    if (!$show_field) return false;
                }
            }
            return true;
        }


        public function condition_applies($element, $post_id){
            if (isset($element['condition'])) {
                $fields = COMPLIANZ()->config->fields();
                $condition_met = true;
                $invert = false;
                foreach ($element['condition'] as $question => $condition_answer) {
                    if ($condition_answer == 'loop') continue;

                    if (!isset($fields[$question]['type'])) return false;

                    $type = $fields[$question]['type'];
                    $value = cmplz_get_value($question, $post_id);


                    if (strpos($condition_answer, 'NOT ')!==FALSE) {
                        $condition_answer = str_replace('NOT ', '', $condition_answer);
                        $invert = true;
                    }

                    if ($type == 'multicheckbox') {
                        if (!isset($value[$condition_answer]) || !$value[$condition_answer]) {
                            $condition_met = false;
                        } else {
                            $condition_met = $condition_met && true;
                        }

                    } else {
                        $condition_met = $condition_met && ($value == $condition_answer);
                    }
                }
                return $invert ? !$condition_met : $condition_met;

            }
            return true;
        }


        /**
         * Check if this element should loop through dynamic multiple values
         * @param array $element
         * @return bool
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

        /**
         * Build a legal document by type
         *
         * @param string $type
         * @param bool|int $post_id
         * @return string
         */

        public function get_document_html($type, $post_id = false)
        {
            if (!isset(COMPLIANZ()->config->document_elements[$type])) return sprintf(__('No %s document was found','complianz-gdpr'),$type);
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
                            if ($fieldname==='used_cookies' && isset($value['show']) && $value['show'] !== 'on') continue;

                            //prevent showing empty cookie entries
                            if ($fieldname==='used_cookies' && (!isset($value['label']) || $value['label'] == '')) continue;

                            if (!is_array($value)) $value = array($value);
                            $fieldnames = array_keys($value);
                            if (count($fieldnames) == 1 && $fieldnames[0] == 'key') continue;

                            $loop_section = $element['content'];
                            foreach ($fieldnames as $c_fieldname) {

                                $field_value = (isset($value[$c_fieldname])) ? $value[$c_fieldname] : '';

                                if (!empty($field_value) && is_array($field_value)) $field_value = implode(', ', $field_value);

                                $loop_section = str_replace('[' . $c_fieldname . ']', $field_value, $loop_section);
                            }

                            $loop_content .= $loop_section;

                        }
                        $html .= $this->wrap_header($element, $paragraph, $sub_paragraph, $annex);
                        $html .= $this->wrap_content($loop_content);
                    }
                } elseif ($this->insert_element($element, $post_id)) {
                    $html .= $this->wrap_header($element, $paragraph, $sub_paragraph, $annex);
                    if (isset($element['content'])) {
                        $html .= $this->wrap_content($element['content'], $element);
                    }
                }
            }

            $html = $this->replace_fields($html, $paragraph_id_arr, $annex_arr, $post_id, $type);

            $comment = apply_filters("cmplz_document_comment", "\n"."<!-- This legal document was generated by Complianz | GDPR Cookie Consent https://wordpress.org/plugins/complianz-gdpr -->"."\n");

            $html =  $comment . '<div id="cmplz-document">'. $html. '</div>';
            $allowed_html = cmplz_allowed_html();
            $html = wp_kses($html, $allowed_html) ;

            return apply_filters('cmplz_document_html', $html, $type, $post_id);
        }





        public function wrap_header($element, $paragraph, $sub_paragraph, $annex)
        {
            $nr = "";
            if (isset($element['annex'])) {
                $nr = __("Annex", 'complianz-gdpr') . " " . $annex . ": ";
                if (isset($element['title'])) {
                    return '<h3 class="annex">' . cmplz_esc_html($nr) . cmplz_esc_html($element['title']) . '</h3>';
                }
                if (isset($element['subtitle'])) {
                    return '<div class="subtitle annex">' . cmplz_esc_html($nr) . cmplz_esc_html($element['subtitle']) . '</div>';
                }
            }

            if (isset($element['title'])) {
                if (empty($element['title'])) return "";
                if ($paragraph > 0 && $this->is_numbered_element($element)) $nr = $paragraph;
                return '<h3>' . cmplz_esc_html($nr) . ' ' . cmplz_esc_html($element['title']) . '</h3>';
            }

            if (isset($element['subtitle'])) {
                if ($paragraph > 0 && $sub_paragraph > 0 && $this->is_numbered_element($element)) $nr = $paragraph . "." . $sub_paragraph . " ";
                return '<div class="cmplz-subtitle">' . cmplz_esc_html($nr) . cmplz_esc_html($element['subtitle']) . '</div>';
            }
        }





        /**
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

        /**
         * Wrap subheader in html
         * @param $header
         * @param $paragraph
         * @param $subparagraph
         * @return string $html
         */

        public function wrap_sub_header($header, $paragraph, $subparagraph)
        {
            if (empty($header)) return "";
            return '<b>' . cmplz_esc_html($header) . '</b><br>';
        }

        public function wrap_content($content, $element = false)
        {
            if (empty($content)) return "";

            $list = (isset($element['list']) && $element['list']) ? true : false;
            //loop content
            if (!$element || $list) {
                return '<div>' . $content . '</div>';
            }
            $p = (!isset($element['p']) || $element['p']) ? true : $element['p'];
            $el = $p ? 'p' : 'div';
            return "<$el>" . $content . "</$el>";
        }

        /**
         * Replace all fields in the resulting output
         * @param $html
         * @param $paragraph_id_arr
         * @param $annex_arr
         * @param $post_id
         * @param $type
         * @return string $html
         */

        private function replace_fields($html, $paragraph_id_arr, $annex_arr, $post_id, $type)
        {
            //replace references
            foreach ($paragraph_id_arr as $id => $paragraph) {
                $html = str_replace("[article-$id]", sprintf(__('(See paragraph %s)', 'complianz-gdpr'), cmplz_esc_html($paragraph['main'])), $html);
            }

            foreach ($annex_arr as $id => $annex) {
                $html = str_replace("[annex-$id]", sprintf(__('(See annex %s)', 'complianz-gdpr'), cmplz_esc_html($annex)), $html);
            }

            //some custom elements
            $html = str_replace("[cookie_accept_text]", cmplz_get_value('accept'), $html);
            $html = str_replace("[cookie_save_preferences_text]", cmplz_get_value('save_preferences'), $html);

            $html = str_replace("[domain]", '<a href="'.cmplz_esc_url_raw(get_home_url()).'">'.cmplz_esc_url_raw(get_home_url()).'</a>', $html);

            $pages = COMPLIANZ()->config->pages;
            //get the region for which this document is meant, default and eu result in empty.

            $region = isset($pages[$type]['condition']['regions']) ? ($pages[$type]['condition']['regions']) : false;
            $html = str_replace("[cookie-statement-url]", cmplz_get_cookie_policy_url($region), $html);

            $html = str_replace("[privacy_policy_url]", $this->get_page_url('privacy-statement',$region), $html);

            //backward compability
            $html = str_replace("[privacy-statement-url]", $this->get_page_url('privacy-statement',$region), $html);
            $html = str_replace("[privacy-statement-children-us-url]", $this->get_page_url('privacy-statement-children','us'), $html);

            //us can have two types of titles
            $cookie_policy_title = esc_html(cmplz_us_cookie_statement_title());
            $html = str_replace('[cookie-statement-us-title]', $cookie_policy_title, $html);

            $date = $post_id ? get_the_date('', $post_id) : get_option('cmplz_publish_date');
            $date = cmplz_localize_date($date);
            $html = str_replace("[publish_date]", cmplz_esc_html($date), $html);

            $checked_date = date(get_option('date_format'), get_option('cmplz_documents_update_date'));
            $checked_date = cmplz_localize_date($checked_date);
            $html = str_replace("[checked_date]", cmplz_esc_html($checked_date), $html);

            //because the phonenumber is not required, we need to allow for an empty phonenr, making a dynamic string necessary.
            $contact_dpo = cmplz_get_value('email_dpo');
            $phone_dpo = cmplz_get_value('phone_dpo');
            if (strlen($phone_dpo)!==0) $contact_dpo .= " ".sprintf(_x("or by telephone on %s",'if phonenumber is entered, this string is part of the sentence "you may contact %s, via %s or by telephone via %s"',"complianz-gdpr"), $phone_dpo);
            $html = str_replace("[email_dpo]", $contact_dpo, $html);

            $contact_dpo_uk = cmplz_get_value('email_dpo_uk');
            $phone_dpo_uk = cmplz_get_value('phone_dpo_uk');
            if (strlen($phone_dpo)!==0) $contact_dpo_uk .= " ".sprintf(_x("or by telephone on %s",'if phonenumber is entered, this string is part of the sentence "you may contact %s, via %s or by telephone via %s"',"complianz-gdpr"), $phone_dpo_uk);
            $html = str_replace("[email_dpo_uk]", $contact_dpo_uk, $html);

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

        /**
         *
         * Get the plain text value of an option
         * @param $fieldname
         * @param $post_id
         * @param bool $list_style
         * @return array|mixed|string
         */


        private function get_plain_text_value($fieldname, $post_id, $list_style = true)
        {
            $value = cmplz_get_value($fieldname, $post_id);

            $front_end_label = isset(COMPLIANZ()->config->fields[$fieldname]['document_label']) ? COMPLIANZ()->config->fields[$fieldname]['document_label'] : false;


            if (COMPLIANZ()->config->fields[$fieldname]['type'] == 'url') {
                $value = '<a href="' . $value . '" target="_blank">';
            } elseif (COMPLIANZ()->config->fields[$fieldname]['type'] == 'email') {
                $value = apply_filters('cmplz_document_email', $value);
            } elseif (COMPLIANZ()->config->fields[$fieldname]['type'] == 'radio') {
                $options = COMPLIANZ()->config->fields[$fieldname]['options'];
                $value = isset($options[$value]) ? $options[$value] : '';
            } elseif(COMPLIANZ()->config->fields[$fieldname]['type'] == 'textarea'){
                //preserve linebreaks
                $value = nl2br($value);
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
                    //trying to fix strange issue where index is not set
                    if (!isset($options[$index])) continue;

                    if ($list_style)
                        $labels .= "<li>" . cmplz_esc_html($options[$index]) . '</li>';
                    else
                        $labels .= $options[$index] . ', ';
                }
                //if (empty($labels)) $labels = __('None','complianz-gdpr');

                if ($list_style) {
                    $labels = "<ul>" . $labels . "</ul>";
                } else {
                    $labels = cmplz_esc_html(rtrim($labels, ', '));
                    $labels = strrev(implode(strrev(' ' . __('and', 'complianz-gdpr')), explode(strrev(','), strrev($labels), 2)));
                }

                $value = $labels;
            } else {
                if (isset(COMPLIANZ()->config->fields[$fieldname]['options'])) {
                    $options = COMPLIANZ()->config->fields[$fieldname]['options'];
                    if (isset($options[$value])) $value = $options[$value];
                }
            }

            if ($front_end_label && !empty($value)) $value = $front_end_label . $value."<br>";
            return $value;
        }

    }
} //class closure