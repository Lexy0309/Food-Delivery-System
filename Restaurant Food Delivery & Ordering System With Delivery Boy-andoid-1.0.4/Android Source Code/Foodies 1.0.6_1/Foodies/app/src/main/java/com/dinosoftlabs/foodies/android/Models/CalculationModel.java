package com.dinosoftlabs.foodies.android.Models;

import java.util.ArrayList;
import java.util.HashMap;

/**
 * Created by Nabeel on 2/16/2018.
 */

public class CalculationModel {

    String key;

    public String getGrandTotal() {
        return grandTotal;
    }

    public void setGrandTotal(String grandTotal) {
        this.grandTotal = grandTotal;
    }

    String grandTotal;

    public String getKey() {
        return key;
    }

    public void setKey(String key) {
        this.key = key;
    }

    public String getmID() {
        return mID;
    }

    public void setmID(String mID) {
        this.mID = mID;
    }

    public String getmName() {
        return mName;
    }

    public void setmName(String mName) {
        this.mName = mName;
    }

    public String getmPrice() {
        return mPrice;
    }

    public void setmPrice(String mPrice) {
        this.mPrice = mPrice;
    }

    public String getmQuantity() {
        return mQuantity;
    }

    public void setmQuantity(String mQuantity) {
        this.mQuantity = mQuantity;
    }

    public String getRequired() {
        return required;
    }

    public void setRequired(String required) {
        this.required = required;
    }

    public CalculationModel(String key,String mID,String mName,String mPrice,String grandTotal,String mQuantity,String required,String minimumOrderPrice,ArrayList<HashMap<String,String>> extraItem,
                            String instruction,String RestID,String mCurrency,String mDesc,String mFee,String mTax){

        this.key = key;
        this.mID = mID;
        this.mName = mName;
        this.mPrice = mPrice;
        this.grandTotal = grandTotal;
        this.mQuantity = mQuantity;
        this.required = required;
        this.minimumOrderPrice = minimumOrderPrice;
        this.instruction = instruction;
        this.restID = RestID;
        this.mCurrency = mCurrency;
        this.mDesc = mDesc;
        this.mFee = mFee;
        this.mTax=mTax;

        this.extraItem = extraItem;

    }

    public String getMinimumOrderPrice() {
        return minimumOrderPrice;
    }

    public void setMinimumOrderPrice(String minimumOrderPrice) {
        this.minimumOrderPrice = minimumOrderPrice;
    }

    String minimumOrderPrice;
    String mID;
    String mName;
    String mPrice;
    String mQuantity;
    String required;

    public String getInstruction() {
        return instruction;
    }

    public void setInstruction(String instruction) {
        instruction = instruction;
    }

    public String getRestID() {
        return restID;
    }

    public void setRestID(String restID) {
        restID = restID;
    }

    public String getmCurrency() {
        return mCurrency;
    }

    public void setmCurrency(String mCurrency) {
        this.mCurrency = mCurrency;
    }

    public String getmDesc() {
        return mDesc;
    }

    public void setmDesc(String mDesc) {
        this.mDesc = mDesc;
    }

    public String getmFee() {
        return mFee;
    }

    public void setmFee(String mFee) {
        this.mFee = mFee;
    }

    public String getmTax() {
        return mTax;
    }

    public void setmTax(String mTax) {
        this.mTax = mTax;
    }

    String instruction,restID,mCurrency,mDesc,mFee,mTax;


    public ArrayList<HashMap<String, String>> getextraItem() {
        return extraItem;
    }

    public void setExtraItem(ArrayList<HashMap<String, String>> ExtraItem) {
        ExtraItem = ExtraItem;
    }

    ArrayList<HashMap<String,String>> extraItem;


}
