package com.dinosoftlabs.foodies.android.Models;

/**
 * Created by Nabeel on 1/3/2018.
 */

public class CountryListModel {

    public String getCountry_name() {
        return country_name;
    }

    public void setCountry_name(String country_name) {
        this.country_name = country_name;
    }

    public String getCountry_img() {
        return country_img;
    }

    public void setCountry_img(String country_img) {
        this.country_img = country_img;
    }

    String country_name;
    String country_img;

    public String getCountry_code() {
        return country_code;
    }

    public void setCountry_code(String country_code) {
        this.country_code = country_code;
    }

    String country_code;
}
