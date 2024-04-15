<?php

namespace InnDigit\Components\Quiz;

use InnDigit\Components\Quiz\Constants;

class ProcessData
{

    public function write_to_db($data)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'inndigit';
        $write_to_db = [];
        foreach ($data as $entry_group) {
            $area_name = '';

            foreach ($entry_group as $entry_index => $entries) {
                if ($area_name === '') {
                    $area_name = $entries['area'];
                }

                if ($area_name !== 'kontakt_oblast_k') {
                    $questionuestion = $entries['label'];
                    $answer = $entries['textAnswer'];
                    $write_to_db[$area_name][$questionuestion][] = $answer;
                } else {
                    if ($entries['label'] === 'Naziv privrednog društva:' || $entries['label'] === 'Kontakt e-mail adresa') {
                        $questionuestion = $entries['label'];
                        $answer = $entries['data']['text'];
                        $write_to_db[$area_name][$questionuestion][] = $answer;
                    }
                }
            }
        }
        $update = [];
        foreach ($write_to_db as $area_name => $area_questions_answers) {
            switch ($area_name) {
                case 'finansije_oblast_f':
                    foreach ($area_questions_answers as $question => $answer) {
                        $update['finansije_q'][] =  $question;
                        $update['finansije_a'][] = $answer;
                    }
                    break;
                case 'kontakt_oblast_k':
                    foreach ($area_questions_answers as $question => $answer) {
                        if ($question === 'Naziv privrednog društva:') {
                            $update['naziv_privrednog_drustva'] = $answer[0];
                        } else {
                            $update['email'] = $answer[0];
                        }
                    }
                    break;
                case 'ljudski_resursi_oblast_h':
                    foreach ($area_questions_answers as $question => $answer) {
                        $update['ljudski_resursi_q'][] =  $question;
                        $update['ljudski_resursi_a'][] = $answer;
                    }
                    break;
                case 'marketing_oblast_m':
                    foreach ($area_questions_answers as $question => $answer) {
                        $update['marketing_q'][] =  $question;
                        $update['marketing_a'][] = $answer;
                    }
                    break;
                case 'proces_oblast_p':
                    foreach ($area_questions_answers as $question => $answer) {
                        $update['proces_q'][] =  $question;
                        $update['proces_a'][] = $answer;
                    }
                    break;
                case 'strategija_oblast_s':
                    foreach ($area_questions_answers as $question => $answer) {
                        $update['strategija_q'][] = $question;
                        $update['strategija_a'][] = $answer;
                    }
                    break;
            }
        }

        $date = time();
        $date = $this->toDateTime($date);





        $result = $wpdb->insert("$table_name", array(
            'finansije_q' => json_encode($update['finansije_q']),
            'finansije_a' => json_encode($update['finansije_a']),
            'naziv_privrednog_drustva' => $update['naziv_privrednog_drustva'],
            'email' => $update['email'],
            'ljudski_resursi_q' => json_encode($update['ljudski_resursi_q']),
            'ljudski_resursi_a' => json_encode($update['ljudski_resursi_a']),
            'marketing_q' => json_encode($update['marketing_q']),
            'marketing_a' => json_encode($update['marketing_a']),
            'proces_q' => json_encode($update['proces_q']),
            'proces_a' => json_encode($update['proces_a']),
            'strategija_q' => json_encode($update['strategija_q']),
            'strategija_a' => json_encode($update['strategija_a']),
            'datum' => $date
        ));
    }

    public static function toDateTime($unixTimestamp)
    {
        return date("Y-m-d H:m:s", $unixTimestamp);
    }

    public function sort($data)
    {
        $sorted_data = [];
        $spec = [];
        foreach ($data as $key => $entry_group) {
            $area_name = '';
            $total_score = 0;

            $contact = [];
            foreach ($entry_group as $entry_index => $entries) {

                if ($area_name === '') {
                    $area_name = $entries['area'];
                }
                $label = $entries['label'];
                $data = $entries['data'];
                if (is_int(intval($data['dataValue']))) {
                    if ($area_name !== 'kontakt_oblast_k') {
                        $score = intval($data['dataValue']);
                        $total_score += $score;
                    } else {
                        $godina = intval($data['dataValue']);
                        $contact[$label]['godina'] = $godina;
                    }
                }
                if ($data['dataSpec']) {
                    $spec[] = $data['dataSpec'];
                }
                if (!is_numeric($data['dataValue'])) {
                    // leave empty for results validity, should be questions & answers
                }
                if ($area_name === 'kontakt_oblast_k') {
                    if ($data['text']) {
                        $sorted_data[$area_name][$label][] = $data['text'];
                    } else {
                        $sorted_data[$area_name][$label][] = $data['dataValue'];
                    }
                }
            }

            $sorted_data[$area_name]['score'] = [$total_score];
        }
        $spec = array_unique($spec);
        $sorted_data['spec'] = $spec;
        $return_data = $this->get_results($sorted_data);
        return $return_data;
    }

    public function get_results($data)
    {
        $total_scores = Constants::$points;
        $specs = Constants::$specs;
        $cummulative = 0;
        $all_specs = [];
        foreach ($total_scores as $area => $score) {
            $percentage = ($data[$area]['score'][0] / $score) * 100;
            $area_letter = strtoupper(substr($area, -1));
            if ($percentage <= 30) {
                $grade = 1;
            } elseif ($percentage > 30 && $percentage <= 70) {
                $grade = 2;
            } else {
                $grade = 3;
            }
            $cummulative += $grade;
            $data[$area]['grade'] = $grade;
        }
        $cummulative = ($cummulative / 5);
        if ($cummulative <= 1.50) {
            $general_result = 'Nizak';
        } elseif ($cummulative > 1.50 && $cummulative <= 2.25) {
            $general_result = 'Srednji';
        } else {
            $general_result = 'Napredni';
        }

        foreach ($data['spec'] as $spec) {
            $spec_text = $specs[$spec];
            if (!empty($spec_text)) {
                $all_specs[] = $spec_text;
            }
        }
        $data['general_result'] = $general_result;
        $data['spec'] = $all_specs;
        return $data;
    }
}
