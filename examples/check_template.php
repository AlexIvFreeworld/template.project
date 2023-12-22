<? foreach ($arResult['DATA_SEND']['ATHLETE'] as $memberKey => $member) : ?>
    <div class="<?= ($memberKey == 0 ? 'f-copy__this' : 'f-copy__delete-this') ?> mrg-t-50" <? if ($memberKey == 0) : ?> data-cur-participant="1" data-count-participants="<?= (count($arResult['ELEMENT']['STAGES']) - count($arResult['DATA_SEND']['MEMBER']) + 1) ?>" <? endif; ?>>
        <div class="form-block form-block--x1">
            <div class="form-block__item">
                <h3 class="text-center">
                    <?= Loc::getMessage("PARTICIPANT") ?>
                    <span class="number-participant"><?= ($memberKey + 1) ?></span>
                    <? if ($memberKey > 0) : ?>
                        <button class="f-copy__delete link" role="button">(<?= Loc::getMessage('MEMBER_DELETE') ?>)</button>
                    <? endif; ?>
                </h3>
            </div>
        </div>
        <? if ($member['REGISTER_MEMBER_ALREADY']) : ?>
            <div class="only-text red-text"><?= Loc::getMessage('REGISTER_MEMBER_ALREADY') ?></div>
        <? endif; ?>
        <? if ($member['REGISTER_MEMBER_EMAIL_EMPTY']) : ?>
            <div class="only-text red-text"><?= Loc::getMessage('REGISTER_MEMBER_EMAIL_EMPTY') ?></div>
        <? endif; ?>
        <div class="form-block form-block--x1">
            <div class="form-block__item">
                <div class="field-radio">
                    <div class="field-radio__input-wrap">
                        <label class="field-radio__name">
                            <input class="field-radio__input" type="radio" name="ATHLETE[<?= $memberKey ?>][CAPTAIN]" value="1" <?= ($arResult['DATA_SEND']['CAPTAIN'] == $memberKey || (!$arResult['DATA_SEND']['ATHLETE'][$memberKey]['CAPTAIN'])  ? ' checked="checked"' : '') ?> data-copy-general="" />
                            <span class="field-radio__name-text"><?= Loc::getMessage('CAPTAIN') ?></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-block">
            <? if ($arResult['ELEMENT']['STAGES']) : ?>
                <? foreach ($arResult['ELEMENT']['STAGES'] as $stage) : ?>
                    <div class="form-block__item">
                        <div class="field-checkbox">
                            <div class="field-checkbox__input-wrap  field-checkbox__input-wrap--type-2">
                                <label class="field-checkbox__name">
                                    <input class="field-checkbox__input" type="checkbox" name="ATHLETE[<?= $memberKey ?>][STAGES]" value="<?= $stage['ID'] ?>" <?= ($member['STAGES'] == $stage['ID'] ? ' checked="checked"' : '') ?> data-checkbox-group="STAGES" data-i="<?= $memberKey ?>" data-j="<?= $stage['ID'] ?>" />
                                    <span class="field-checkbox__name-text">
                                        <?= $stage['PROPERTY_ICON_CODE'] ?>
                                        <?= $stage['DESCRIPTION'] ?>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                <? endforeach; ?>
            <? endif; ?>
            <? foreach ($arResult["SHOW_FIELDS"] as $field) :
                $value = '';
                if ($memberKey == 0) {
                    $value = $arResult['USER'][$field];
                } else if ($member[$field]) {
                    $value = $member[$field];
                }
            ?>
                <? switch ($field) {
                    case 'UF_CLUB':
                        if ($memberKey == 0) {
                            $value = current($arResult['USER'][$field]);
                        }
                ?>
                        <div class="form-block__item">
                            <div class="field-select  field-select--type-2">
                                <div class="field-select__select-wrap">
                                    <? if ($arResult['UF_CLUB_LIST']) : ?>
                                        <select class="field-select__select" name="ATHLETE[<?= $memberKey ?>][UF_CLUB]" <?= ($memberKey == 0 ? ' disabled' : '') ?> data-select-html="<select class='field-select__select' name='ATHLETE[<?= $memberKey ?>][UF_CLUB]">
                                            <option value=''><?= Loc::getMessage("UF_C_TITLE") ?><?= Loc::getMessage("REGISTER_FIELD_" . $field) ?></option>
                                            <? foreach ($arResult['UF_CLUB_LIST'] as $key => $listClub) : ?>
                                                <option value='<?= $key ?>'><?= $listClub ?></option>
                                            <? endforeach; ?>
                                            <option value=""><?= Loc::getMessage("UF_C_TITLE") ?><?= Loc::getMessage("REGISTER_FIELD_" . $field) ?></option>
                                            <? foreach ($arResult['UF_CLUB_LIST'] as $key => $listClub) : ?>
                                                <option value="<?= $key ?>" <?= ($key == $value ? ' selected' : '') ?>><?= $listClub ?></option>
                                            <? endforeach; ?>
                                        </select>
                                    <? endif; ?>
                                </div>
                            </div>
                        </div>
                    <? break;
                    case 'PERSONAL_GENDER':
                    ?>
                        <div class="form-block__item">
                            <div class="field-radio field-radio--row">
                                <div class="field-radio__title"><?= Loc::getMessage("REGISTER_FIELD_" . $field) ?></div>
                                <div class="field-radio__input-wrap">
                                    <label class="field-radio__name">
                                        <input class="field-radio__input" type="radio" name="ATHLETE[<?= $memberKey ?>][<?= $field ?>]" value="M" <?= ($value == "M" ? " checked" : ($memberKey == 0 ? " disabled" : '')) ?> />
                                        <span class="field-radio__name-text"><?= Loc::getMessage("USER_MALE") ?></span></label>
                                </div>
                                <div class="field-radio__input-wrap">
                                    <label class="field-radio__name">
                                        <input class="field-radio__input" type="radio" name="ATHLETE[<?= $memberKey ?>][<?= $field ?>]" value="F" <?= ($value == "F" ? " checked" : ($memberKey == 0 ? " disabled" : '')) ?> />
                                        <span class="field-radio__name-text"><?= Loc::getMessage("USER_FEMALE") ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <? break;
                    default:
                    ?>
                        <div class="form-block__item">
                            <label class="field-text">
                                <span class="field-text__name"><?= Loc::getMessage("REGISTER_FIELD_" . $field) ?></span>
                                <span class="field-text__input-wrap">
                                    <input class="field-text__input" type="text" name="ATHLETE[<?= $memberKey ?>][<?= $field ?>]" inputmode="text" placeholder="<?= Loc::getMessage("REGISTER_FIELD_" . $field) ?><?= (in_array($field, $arResult['REQUIRED_FIELDS']) ? '*' : '') ?>" data-check-pattern="<?= Loc::getMessage("PATTERN_FIELD_" . $field) ?>" <?= ($memberKey == 0 ? ' disabled' : '') ?> value="<?= $value ?>" />
                                    <span class="field-text__help-text"><?= Loc::getMessage("HELP_FIELD_" . $field) ?></span>
                                </span>
                            </label>
                        </div>
                <? break;
                } ?>
            <? endforeach; ?>
            <input type="hidden" name="MEMBER[]" value="Y" />
        </div>
    </div>
<? endforeach; ?>
<div class="form-block form-block--x1 form-block--center mrg-t-50 f-copy__put-before-this">
    <button <? if (!(count($arResult['ELEMENT']['STAGES']) > 1 && count($arResult['ELEMENT']['STAGES']) > count($arResult['DATA_SEND']['MEMBER']))) : ?> style="display: none;" <? endif; ?> class="btn  btn--big f-copy__btn" type="button"><span><?= Loc::getMessage("ADD_PARTICIPANT") ?></span> </button>
</div>