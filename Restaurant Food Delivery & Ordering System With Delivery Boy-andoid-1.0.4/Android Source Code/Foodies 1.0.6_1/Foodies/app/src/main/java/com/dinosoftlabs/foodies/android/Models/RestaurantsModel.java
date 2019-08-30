package com.dinosoftlabs.foodies.android.Models;

/**
 * Created by Nabeel on 12/20/2017.
 */

public class RestaurantsModel {

    String  restaurant_name = "";
    String  restaurant_salgon= "";
    String  restaurant_about= "";
    String  restaurant_fee= "";
    String  restaurant_image= "";
    String  restaurant_cover= "";
    String  restaurant_id= "";
    String  restaurant_menu_style= "";
    String  restaurant_phone= "";
    String  restaurant_totalRating= "";
    String  restaurant_avgRating= "";
    String  restaurant_isFav= "";
    String  restaurant_distance= "";
    String  restaurant_currency= "";
    String  restaurant_tax= "";

    public String getDeliveryTime() {
        return deliveryTime;
    }

    public void setDeliveryTime(String deliveryTime) {
        this.deliveryTime = deliveryTime;
    }

    String deliveryTime = "";

    public String getDeliveryFee_Range() {
        return deliveryFee_Range;
    }

    public void setDeliveryFee_Range(String deliveryFee_Range) {
        this.deliveryFee_Range = deliveryFee_Range;
    }

    String deliveryFee_Range;

    public String getDelivery_fee_per_km() {
        return delivery_fee_per_km;
    }

    public void setDelivery_fee_per_km(String delivery_fee_per_km) {
        this.delivery_fee_per_km = delivery_fee_per_km;
    }

    public String getMin_order_price() {
        return min_order_price;
    }

    public void setMin_order_price(String min_order_price) {
        this.min_order_price = min_order_price;
    }

    String delivery_fee_per_km,min_order_price;

    public String getPreparation_time() {
        return preparation_time;
    }

    public void setPreparation_time(String preparation_time) {
        this.preparation_time = preparation_time;
    }

    String preparation_time = "";

    public void setRestaurant_salgon(String restaurant_salgon) {
        this.restaurant_salgon = restaurant_salgon;
    }

    public String getRestaurant_menu_style() {
        return restaurant_menu_style;
    }

    public void setRestaurant_menu_style(String restaurant_menu_style) {
        this.restaurant_menu_style = restaurant_menu_style;
    }

    public String getPromoted() {
        return promoted;
    }

    public void setPromoted(String promoted) {
        this.promoted = promoted;
    }

    String promoted = "";

    public RestaurantsModel(String restaurant_name,String restaurant_salgon,String restaurant_image,String restaurant_distance,String restaurant_about,String restaurant_fee,String restaurant_cover,String restaurant_id,String restaurant_menu_style,String restaurant_phone,String restaurant_totalRating,String restaurant_avgRating,String restaurant_isFav,String restaurant_currency, String restaurant_tax){

        this.restaurant_distance=restaurant_distance;
        this.restaurant_image=restaurant_image;
        this.restaurant_salgon=restaurant_salgon;
        this.restaurant_name=restaurant_name;
        this.restaurant_about=restaurant_about;
        this.restaurant_fee=restaurant_fee;
        this.restaurant_cover=restaurant_cover;
        this.restaurant_id=restaurant_id;
        this.restaurant_menu_style=restaurant_menu_style;
        this.restaurant_phone=restaurant_phone;
        this.restaurant_totalRating=restaurant_totalRating;
        this.restaurant_avgRating=restaurant_avgRating;
        this.restaurant_isFav=restaurant_isFav;
        this.restaurant_currency=restaurant_currency;
        this.restaurant_tax=restaurant_tax;



    }


    public String getRestaurant_name() {
        return restaurant_name;
    }

    public void setRestaurant_name(String restaurant_name) {
        this.restaurant_name = restaurant_name;
    }

    public String getRestaurant_salgon() {
        return restaurant_salgon;
    }

    public void setRestaurant_slogen(String restaurant_salgon) {
        this.restaurant_salgon = restaurant_salgon;
    }

    public String getRestaurant_image() {
        return restaurant_image;
    }

    public void setRestaurant_image(String restaurant_image) {
        this.restaurant_image = restaurant_image;
    }

    public String getRestaurant_distance() {
        return restaurant_distance;
    }

    public void setRestaurant_distance(String restaurant_distance) {
        this.restaurant_distance = restaurant_distance;
    }
    public String getRestaurant_about() {
        return restaurant_about;
    }

    public void setRestaurant_about(String restaurant_about) {
        this.restaurant_about = restaurant_about;
    }
    public String getRestaurant_fee() {
        return restaurant_fee;
    }

    public void setRestaurant_fee(String restaurant_fee) {
        this.restaurant_fee = restaurant_fee;
    }
    public String getRestaurant_cover() {
        return restaurant_cover;
    }

    public void setRestaurant_cover(String restaurant_cover) {
        this.restaurant_cover = restaurant_cover;
    }
    public String getRestaurant_id() {
        return restaurant_id;
    }

    public void setRestaurant_id(String restaurant_id) {
        this.restaurant_id = restaurant_id;
    }
    public String getRestaurant_restaurant_menu_style() {
        return restaurant_menu_style;
    }

    public void setRestaurant_restaurant_menu_style(String restaurant_menu_style) {
        this.restaurant_menu_style = restaurant_menu_style;
    }
    public String getRestaurant_phone() {
        return restaurant_phone;
    }

    public void setRestaurant_phone(String restaurant_phone) {
        this.restaurant_phone = restaurant_phone;
    }
    public String getRestaurant_totalRating() {
        return restaurant_totalRating;
    }

    public void setRestaurant_totalRating(String restaurant_about) {
        this.restaurant_totalRating = restaurant_totalRating;
    }
    public String getRestaurant_avgRating() {
        return restaurant_avgRating;
    }

    public void setRestaurant_avgRating(String restaurant_avgRating) {
        this.restaurant_avgRating = restaurant_avgRating;
    }
    public String getRestaurant_isFav() {
        return restaurant_isFav;
    }

    public void setRestaurant_isFav(String restaurant_isFav) {
        this.restaurant_isFav = restaurant_isFav;
    }
    public String getRestaurant_currency() {
        return restaurant_currency;
    }

    public void setRestaurant_currency(String restaurant_currency) {
        this.restaurant_currency = restaurant_currency;
    }
    public String getRestaurant_tax() {
        return restaurant_tax;
    }

    public void setRestaurant_tax(String restaurant_tax) {
        this.restaurant_tax = restaurant_tax;
    }


    public RestaurantsModel(){

    }

}
