package com.dinosoftlabs.foodies.android.Models;

/**
 * Created by Nabeel on 12/27/2017.
 */

public class DealsModel {

    public String getDeal_id() {
        return deal_id;
    }

    public void setDeal_id(String deal_id) {
        this.deal_id = deal_id;
    }

    public String getDeal_desc() {
        return deal_desc;
    }

    public void setDeal_desc(String deal_desc) {
        this.deal_desc = deal_desc;
    }

    public String getDeal_expiry_date() {
        return deal_expiry_date;
    }

    public void setDeal_expiry_date(String deal_expiry_date) {
        this.deal_expiry_date = deal_expiry_date;
    }

    public String getDeal_name() {
        return deal_name;
    }

    public void setDeal_name(String deal_name) {
        this.deal_name = deal_name;
    }

    public String getDeal_price() {
        return deal_price;
    }

    public void setDeal_price(String deal_price) {
        this.deal_price = deal_price;
    }

    public String getDeal_restaurant_id() {
        return deal_restaurant_id;
    }

    public void setDeal_restaurant_id(String deal_restaurant_id) {
        this.deal_restaurant_id = deal_restaurant_id;
    }

    public String getDeal_cover_image() {
        return deal_cover_image;
    }

    public void setDeal_cover_image(String deal_cover_image) {
        this.deal_cover_image = deal_cover_image;
    }

    public String getRestaurant_name() {
        return restaurant_name;
    }

    public void setRestaurant_name(String restaurant_name) {
        this.restaurant_name = restaurant_name;
    }

    public String getDeal_image() {
        return deal_image;
    }

    public void setDeal_image(String deal_image) {
        this.deal_image = deal_image;
    }

    public String getDeal_delivery_fee() {
        return deal_delivery_fee;
    }

    public void setDeal_delivery_fee(String deal_delivery_fee) {
        this.deal_delivery_fee = deal_delivery_fee;
    }

    public String getDeal_symbol() {
        return deal_symbol;
    }

    public void setDeal_symbol(String deal_symbol) {
        this.deal_symbol = deal_symbol;
    }

    String  deal_id = "";
    String  deal_desc= "";
    String  deal_expiry_date= "";
    String  deal_name= "";
    String  deal_price= "";
    String  deal_restaurant_id= "";
    String  deal_cover_image= "";
    String  restaurant_name= "";
    String deal_image = "";
    String  deal_delivery_fee= "";
    String  deal_symbol= "";

    public String getIsDeliveryFree() {
        return isDeliveryFree;
    }

    public void setIsDeliveryFree(String isDeliveryFree) {
        this.isDeliveryFree = isDeliveryFree;
    }

    String isDeliveryFree = "";

    public String getDeal_tax() {
        return deal_tax;
    }

    public void setDeal_tax(String deal_tax) {
        this.deal_tax = deal_tax;
    }

    String deal_tax = "";

    public String getPromoted() {
        return promoted;
    }

    public void setPromoted(String promoted) {
        this.promoted = promoted;
    }

    String promoted;
}
