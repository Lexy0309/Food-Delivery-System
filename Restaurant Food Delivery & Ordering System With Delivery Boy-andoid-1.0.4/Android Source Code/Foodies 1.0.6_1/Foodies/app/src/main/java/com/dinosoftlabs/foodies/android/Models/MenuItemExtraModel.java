package com.dinosoftlabs.foodies.android.Models;

/**
 * Created by Nabeel on 1/9/2018.
 */

public class MenuItemExtraModel {
    String price;

    public String getPrice() {
        return price;
    }

    public void setPrice(String price) {
        this.price = price;
    }

    public String getQuantity() {
        return quantity;
    }

    public void setQuantity(String quantity) {
        this.quantity = quantity;
    }

    public String getExtra_item_name() {
        return extra_item_name;
    }

    public void setExtra_item_name(String extra_item_name) {
        this.extra_item_name = extra_item_name;
    }

    String quantity;
    String extra_item_name;

    public String getCurrency() {
        return currency;
    }

    public void setCurrency(String currency) {
        this.currency = currency;
    }

    String currency;
}
