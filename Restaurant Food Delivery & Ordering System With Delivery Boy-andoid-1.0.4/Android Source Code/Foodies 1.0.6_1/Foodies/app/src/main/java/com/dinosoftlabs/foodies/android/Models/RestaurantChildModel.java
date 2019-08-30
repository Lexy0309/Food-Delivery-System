package com.dinosoftlabs.foodies.android.Models;

/**
 * Created by Nabeel on 1/10/2018.
 */

public class RestaurantChildModel {

    public String getPrice() {
        return price;
    }

    public void setPrice(String price) {
        this.price = price;
    }

    public String getChild_title() {
        return child_title;
    }

    public void setChild_title(String child_title) {
        this.child_title = child_title;
    }

    public String getChild_sub_title() {
        return child_sub_title;
    }

    public void setChild_sub_title(String child_sub_title) {
        this.child_sub_title = child_sub_title;
    }

    String price;
    String child_title;
    String child_sub_title;

    public String getOrder_detail() {
        return order_detail;
    }

    public void setOrder_detail(String order_detail) {
        this.order_detail = order_detail;
    }

    String order_detail;

    public String getRestaurant_menu_item_id() {
        return restaurant_menu_item_id;
    }

    public void setRestaurant_menu_item_id(String restaurant_menu_item_id) {
        this.restaurant_menu_item_id = restaurant_menu_item_id;
    }

    String restaurant_menu_item_id;

    public String getCurrency_symbol() {
        return currency_symbol;
    }

    public void setCurrency_symbol(String currency_symbol) {
        this.currency_symbol = currency_symbol;
    }

    String currency_symbol;
}
