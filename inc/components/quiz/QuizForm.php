<?php

namespace InnDigit\Components\Quiz;

use InnDigit\Components\Quiz\Constants;

class QuizForm
{

    public function data($fields)
    {
        if ($fields) {
            // $fields = array_reverse($fields);
            $tabs_array = [];
            $content_array = [];
            foreach ($fields as $key => $field) {
                $tabs_array[] = [$field['label'], $field['name'], $key];
                $content_array[] = [$field['name'], $field['sub_fields'], $field['label']];
            }
            $tabs = $this->create_tabs($tabs_array);

            $content = $this->create_content($content_array);
            $submit = $this->create_submit();
            $message = $this->create_message();
            $quiz = $this->create_quiz($tabs, $content, $message, $submit);
            return $quiz;
        }
    }

    public function create_tabs($tabs)
    {

        $image_constants = Constants::$image_urls;
        $html = '<div class="quiz-tab">'; 
        foreach ($tabs as $tab) {
            if ($tab[2] == 0) {
                $html .= '<div class="tablinks active" data-wrapper="' . $tab[1] . '">';
                $html .= '<img src="' . $image_constants[$tab[1]] . '">';
                $html .= '<span name="' . $tab[1] . '">' . $tab[0] . '</span>';
                $html .= '</div>';
            } else {
                $html .= '<div class="tablinks" data-wrapper="' . $tab[1] . '">';
                $html .= '<img src="' . $image_constants[$tab[1]] . '">';
                $html .= '<span name="' . $tab[1] . '">' . $tab[0] . '</span>';
                $html .= '</div>';
            }
        }
        $html .= '</div>';
        return $html;
    }

    public function create_content($content_array)
    {
        $html = '<div class="tab-content-wrapper">';

        foreach ($content_array as $key => $content_item) {
            $name = $content_item[0];
            $sub_fields_array = $content_item[1];
            $label = $content_item[2];



            if ($key == 0) {
                $html .= '<div class="tab-content ' . $name . ' active">';
            } else {
                $html .= '<div class="tab-content ' . $name . ' ">';
            }

            $html .= '<h2>' . $label . '</h2>';

            foreach ($sub_fields_array as $sub_field) {

                $type = $sub_field['type']; //Text Select Radio Button Email True / False Checkbox
                switch ($type) {
                    case 'text':
                        $html .= '<div class="field-wrap ' . $sub_field['name'] .  ' ">';
                        $html .= '<label for="' . $sub_field['name'] . '">' . $sub_field['label'];
                        $html .= '<div class="input-wrap ' . $type . '">';
                        $html .= '<input type="text" class="' . $sub_field['name'] . '" name="' . $sub_field['name'] . '">';
                        $html .= '</div>';
                        $html .= '</label>';
                        $html .= '</div>';
                        break;
                    case 'select':
                        $html .= '<div class="field-wrap answered ' . $sub_field['name'] .  ' ">';
                        $html .= '<label for="' . $sub_field['name'] . '">' . $sub_field['label'] . '</label>';
                        $html .= '<div class="input-wrap ' . $type . '">';
                        $html .= '<select class="' . ' ' . $type . ' ' . $sub_field['name'] . '" name="' . $sub_field['name'] . '">';
                        foreach ($sub_field['choices'] as $choice => $quiz_value) {
                            $html .= '<option data-quiz-value="' . $quiz_value . '" value="' . $choice . '">' . $choice . '</option>';
                        }
                        $html .= '</select>';
                        $html .= '</div>';
                        $html .= '</div>';
                        break;
                    case 'radio':
                        $html .= '<div class="field-wrap ' . $sub_field['name'] .  ' ">';
                        $html .= '<label for="' . $sub_field['name'] . '">' . $sub_field['label'] . '</label>';
                        $html .= '<div class="input-wrap ' . $type . '">';
                        foreach ($sub_field['choices'] as $choice => $quiz_value) {
                            $html .= '<div class="checkbox-wrap">' . $choice;
                            $html .= '<label class="checkbox-container" for="' . $choice . '">';
                            $html .= '<input data-quiz-value="' . $quiz_value . '" type="radio" class="' . $choice . '" name="' . $sub_field['name'] . '[]" value="' . $choice . '">';
                            $html .= '<span class="checkmark"></span></label>';
                            $html .= '</div>';
                        }
                        $html .= '</div>';
                        $html .= '</div>';
                        break;
                    case 'email':
                        $html .= '<div class="field-wrap ' . $sub_field['name'] .  ' ">';
                        $html .= '<label for="' . $sub_field['name'] . '">' . $sub_field['label'] . '</label>';
                        $html .= '<div class="input-wrap ' . $type . '">';
                        $html .= '<input type="email" class="' . $sub_field['name'] . '" name="' . $sub_field['name'] . '">';
                        $html .= '</div>';
                        $html .= '</div>';
                        break;
                    case 'true_false':
                        $html .= '<div class="field-wrap ' . $sub_field['name'] .  ' ">';
                        $html .= '<div class="input-wrap checkbox">';
                        $html .= '<div class="checkbox-wrap">';
                        $html .= '<label class="checkbox-container-confirmation" for="' . $sub_field['name'] . '">' . $sub_field['label'];
                        $html .= '<div>';
                        $html .= '<input type="checkbox" class="checkbox-confirmation ' . $sub_field['name'] . '" name="' . $sub_field['name'] . '">' . $sub_field['message'] . '<span class="checkmark-confirmation"></span></label>';
                        $html .= '</div>';
                        $html .= '</label>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '</div>';
                        break;
                    case 'checkbox':
                        $html .= '<div class="field-wrap ' . $sub_field['name'] .  ' ">';
                        $html .= '<label>' . $sub_field['label'] . '</label>';
                        $html .= '<div class="input-wrap ' . $type . '">';
                        foreach ($sub_field['choices'] as $choice => $quiz_value) {
                            $html .= '<div class="checkbox-wrap">' . $choice;
                            $html .= '<label class="checkbox-container" for="' . $choice . '">';
                            $html .= '<input data-quiz-value="' . $quiz_value . '" type="checkbox" class="' . $choice . '" name="' . $sub_field['name'] . '[]" value="' . $choice . '">';
                            $html .= '<span class="checkmark"></span></label>';
                            $html .= '</div>';
                        }
                        $html .= '</div>';
                        $html .= '</div>';
                        break;
                }
            }
            $html .= '</div>';
        }
        $html .= '</div>';
        return $html;
    }

    public function create_message()
    {
        $html = '<div class="message-wrap">';
        $html .= '<div class="message">';
        $html .= '</div>';
        $html .= '<button id="message-button" class="button">Zatvori</button>';
        $html .= '</div>';
        return $html;
    }

    public function create_submit()
    {
        $html = '<div class="submit-wrap">';
        $html .= '<button class="submit-quiz button --double">Nastavi</button>';
        $html .= '</div>';
        return $html;
    }



    public function create_quiz($tabs, $content, $message, $submit)
    {
        $html = '<div class="quiz">';
        $html .= $tabs;
        $html .= $content;
        $html .= $message;
        $html .= $submit;
        $html .= '</div>';
        return $html;
    }
}
