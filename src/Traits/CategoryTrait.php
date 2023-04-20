<?php

namespace BossmanFilamentApp\Traits;

trait CategoryTrait
{


    private static function treeChildrenBuilder(&$array, &$output, $separator, $treeSeparator): void
    {
        if ($array->children) {
            $separator = $separator . $treeSeparator;
            foreach ($array->children as $child) {
                $output[$child->id] = $separator . ' ' . $child->name;
                self::treeChildrenBuilder($child, $output, $separator, $treeSeparator);
            }
        }
    }

    public static function initMenuChildrenTree($arrays, $treeSeparator = '->', $output = [],)
    {
        $separator = $treeSeparator;
        foreach ($arrays as $key => $array) {
            $output[$array->id] = $separator . ' ' . $array->name;
            if ($array->children) {
                self::treeChildrenBuilder($array, $output, $separator, $treeSeparator);
            }
        }
        return $output;
    }


    private static function treeParentBuilder(&$array, &$output, $separator, $treeSeparator): void
    {
        if(is_array($array['name'])){
            $nameKey = array_keys($array['name']);
            $defaultLanguageKey = $nameKey[0] ?? 'ka';
            $name = $array['name'][$defaultLanguageKey];
        }else{
            $nameKey = $array['name'];
            $name = $nameKey;
        }
        $output[$array['id']] = $separator . ' ' . $name;
        if (isset($array['all_parents'])) {
            self::treeParentBuilder($array['all_parents'], $output, $separator, $treeSeparator);
        }
    }


    public static function initMenuParentTree($arrays, $treeSeparator = '<span class="parent-category_sub-tree">&#10040;</span>', $output = [],)
    {
        $separator = $treeSeparator;

        if ($arrays->allParents) {
            $parentsArray = $arrays->allParents->toArray();

            if(is_array($parentsArray['name'])){
                $nameKey = array_keys($parentsArray['name']);
                $defaultLanguageKey = $nameKey[0] ?? 'ka';
                $name = $parentsArray['name'][$defaultLanguageKey];
            }else{
                $nameKey = $parentsArray['name'];
                $name = $nameKey;
            }




            $output[$parentsArray['id']] = $separator . ' ' . $name;
            if (isset($parentsArray['all_parents'])) {
                self::treeParentBuilder($parentsArray['all_parents'], $output, $separator, $treeSeparator);
            }
        } else {
            $output[] = __('Root Category');
        }
        return array_reverse($output);
    }


}
