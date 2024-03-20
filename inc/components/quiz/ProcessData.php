<?php

namespace InnDigit\Components\Quiz;

use InnDigit\Components\Quiz\Constants;

class ProcessData
{

    public function sort($data)
    {
        $sorted_data = [];
        $spec = [];
        foreach ($data as $key => $entry_group) {
            $area_name = '';
            $total_score = 0;

            $questions_and_text_answers = [];
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
                    // $questions_and_text_answers[$label][] = $data['dataValue'];
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

            // $sorted_data[$area_name]['questions_and_text_answers'] = $questions_and_text_answers;
        }
        $spec = array_unique($spec);
        $sorted_data['spec'] = $spec;
        $return_data = $this->get_results($sorted_data);
        return $return_data;
    }

    public function get_results($data)
    {
        $total_scores = Constants::$points;
        $comments = Constants::$comments;
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
            $data[$area]['comment'] = $comments[$area_letter . $grade];
        }
        $cummulative = ($cummulative / 5);
        if ($cummulative <= 1.09) {
            $general_result = 'Nizak';
        } elseif ($cummulative > 1.09 && $cummulative <= 2) {
            $general_result = 'Srednji';
        } else {
            $general_result = 'Visok';
        }

        foreach ($data['spec'] as $spec) {
            $spec_text = $specs[$spec];
            if (!empty($spec_text)) {
                $all_specs[] = $spec_text;
            }
        }
        $data['general'] = $general_result;
        $data['spec'] = $all_specs;
        return $data;
    }
}
