package com.dinosoftlabs.foodies.android.Models;

/**
 * Created by Nabeel on 1/22/2018.
 */

public class CartChildModel {
    public String getChild_item_name() {
        return child_item_name;
    }

    public void setChild_item_name(String child_item_name) {
        this.child_item_name = child_item_name;
    }

    public String getChild_item_price() {
        return child_item_price;
    }

    public void setChild_item_price(String child_item_price) {
        this.child_item_price = child_item_price;
    }

    String child_item_name,child_item_price;


    public boolean isCheckedddd() {
        return isChecked;
    }

    public boolean setCheckeddd(boolean checked) {
        isChecked = checked;
        return checked;
    }

    boolean isChecked = false;

    public boolean isCheckBoxIsChecked() {
        return checkBoxIsChecked;
    }

    public void setCheckBoxIsChecked(boolean checkBoxIsChecked) {
        this.checkBoxIsChecked = checkBoxIsChecked;
    }

    boolean checkBoxIsChecked = false;

    public boolean isCheckRequired() {
        return checkRequired;
    }

    public void setCheckRequired(boolean checkRequired) {
        this.checkRequired = checkRequired;
    }

    boolean checkRequired;

    public int getPos() {
        return pos;
    }

    public void setPos(int pos) {
        this.pos = pos;
    }

    public int pos;

    public String getExtra_item_id() {
        return extra_item_id;
    }

    public void setExtra_item_id(String extra_item_id) {
        this.extra_item_id = extra_item_id;
    }

    String extra_item_id;

    public String getSymbol() {
        return symbol;
    }

    public void setSymbol(String symbol) {
        this.symbol = symbol;
    }

    String symbol;

}
