package com.dinosoftlabs.foodies.android.Models;

/**
 * Created by Nabeel on 12/26/2017.
 */

public class OrderModelClass {

    public String getOrder_id() {
        return order_id;
    }

    public void setOrder_id(String order_id) {
        this.order_id = order_id;
    }

    public String getOrder_name() {
        return order_name;
    }

    public void setOrder_name(String order_name) {
        this.order_name = order_name;
    }

    public String getOrder_quantity() {
        return order_quantity;
    }

    public void setOrder_quantity(String order_quantity) {
        this.order_quantity = order_quantity;
    }

    public String getOrder_price() {
        return order_price;
    }

    public void setOrder_price(String order_price) {
        this.order_price = order_price;
    }

    public String getOrder_menu_id() {
        return order_menu_id;
    }

    public void setOrder_menu_id(String order_menu_id) {
        this.order_menu_id = order_menu_id;
    }

    public String getOrder_created() {
        return order_created;
    }

    public void setOrder_created(String order_created) {
        this.order_created = order_created;
    }

    public String getCurrency_symbol() {
        return currency_symbol;
    }

    public void setCurrency_symbol(String currency_symbol) {
        this.currency_symbol = currency_symbol;
    }

    public String getRestaurant_name() {
        return restaurant_name;
    }

    public void setRestaurant_name(String restaurant_name) {
        this.restaurant_name = restaurant_name;
    }

    public String getOrder_extra_item_name() {
        return order_extra_item_name;
    }

    public void setOrder_extra_item_name(String order_extra_item_name) {
        this.order_extra_item_name = order_extra_item_name;
    }

    String  order_id = "";
    String  order_name= "";
    String  order_quantity= "";
    String  order_price= "";
    String  order_menu_id= "";
    String  order_created= "";
    String  currency_symbol= "";
    String  restaurant_name= "";
    String  order_extra_item_name= "";

    public String getDeal_id() {
        return deal_id;
    }

    public void setDeal_id(String deal_id) {
        this.deal_id = deal_id;
    }

    String deal_id = "";

    public String getDelivery() {
        return delivery;
    }

    public void setDelivery(String delivery) {
        this.delivery = delivery;
    }

    String delivery="";

    public String getInstructions() {
        return instructions;
    }

    public void setInstructions(String instructions) {
        this.instructions = instructions;
    }

    String instructions ="";

    public String getOrder_number() {
        return order_number;
    }

    public void setOrder_number(String order_number) {
        this.order_number = order_number;
    }

    String order_number="";

}
