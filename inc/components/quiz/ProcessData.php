<?php

namespace InnDigit\Components\Quiz;

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
                    $questions_and_text_answers[$label][] = $data['dataValue'];
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

            $sorted_data[$area_name]['questions_and_text_answers'] = $questions_and_text_answers;
        }
        $spec = array_unique($spec);
        $sorted_data['spec'] = $spec;
        return $sorted_data;
    }

    public function get_results($data)
    {
        $result = '';
        if ($data['score'] <= 2) {
            $result = 'Niste spremni za digitalizaciju';
        } else if ($data['score'] <= 4) {
            $result = 'Spremni ste za digitalizaciju';
        } else {
            $result = 'Veoma ste spremni za digitalizaciju';
        }
        $data['result'] = $result;
        return $data;
    }
}
