package com.dinosoftlabs.foodies.android.Adapters;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseExpandableListAdapter;
import android.widget.CheckBox;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.TextView;

import com.dinosoftlabs.foodies.android.Models.CartChildModel;
import com.dinosoftlabs.foodies.android.Models.CartParentModel;
import com.dinosoftlabs.foodies.android.R;


import java.util.ArrayList;

/**
 * Created by Nabeel on 1/22/2018.
 */

public class AddToCartExpandable extends BaseExpandableListAdapter {

    Context context;
    ArrayList<CartParentModel> ListTerbaru;
    ArrayList<ArrayList<CartChildModel>> ListChildTerbaru;
    private RadioButton lastCheckedRB = null;
    private int selectedPosition = -1;
    public static boolean FLAG_CHECKBOX;
    private int selectedIndex = -1;
    private int mSelectedVariation;

    public AddToCartExpandable (Context context, ArrayList<CartParentModel>ListTerbaru, ArrayList<ArrayList<CartChildModel>> ListChildTerbaru){
        this.context=context;
        this.ListTerbaru=ListTerbaru;
        this.ListChildTerbaru=ListChildTerbaru;
//      this.count=ListTerbaru.size();
//      this.count=ListChildTerbaru.size();
    }
    @Override
    public boolean areAllItemsEnabled()
    {
        return true;
    }


    @Override
    public CartChildModel getChild(int groupPosition, int childPosition) {
        return ListChildTerbaru.get(groupPosition).get(childPosition);
    }

    @Override
    public long getChildId(int groupPosition, int childPosition)
    {
        return childPosition;
    }



    @Override
    public View getChildView(int groupPosition, final int childPosition, boolean isLastChild, View convertView, ViewGroup parent) {

        final CartChildModel childTerbaru = getChild(groupPosition, childPosition);

        AddToCartExpandable.ViewHolder holder= null;
        notifyDataSetChanged();

        if (convertView == null) {
            LayoutInflater infalInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            convertView = infalInflater.inflate(R.layout.row_item_add_to_cart_child, null);

            holder=new AddToCartExpandable.ViewHolder();
            holder.radio_btn_item_name=(TextView)convertView.findViewById(R.id.radio_btn_item_name);
            holder.item_price_tv=(TextView)convertView.findViewById(R.id.item_price_tv);

            holder.radio_btn = convertView.findViewById(R.id.radio_btn);
         //  holder.radio_btn_group = convertView.findViewById(R.id.radio_btn_group);
           holder.check_btn = convertView.findViewById(R.id.check_btn);


            convertView.setTag(holder);
        }
        else{
            holder=(ViewHolder)convertView.getTag();
        }


        holder.radio_btn_item_name.setText(childTerbaru.getChild_item_name());
        holder.item_price_tv.setText("+ "+childTerbaru.getSymbol()+childTerbaru.getChild_item_price());

            if (childTerbaru.isCheckedddd()) {
                holder.radio_btn.setChecked(true);

            } else {
                holder.radio_btn.setChecked(false);
            }


        if(FLAG_CHECKBOX){
            childTerbaru.setCheckRequired(false);
            holder.radio_btn.setVisibility(View.INVISIBLE);
            holder.check_btn.setVisibility(View.VISIBLE);
        }
        else {
            childTerbaru.setCheckRequired(true);
            holder.radio_btn.setVisibility(View.VISIBLE);
            holder.check_btn.setVisibility(View.GONE);
        }


        return convertView;
    }
    @Override
    public int getChildrenCount(int groupPosition) {
        return ListChildTerbaru.get(groupPosition).size();
    }

    public ArrayList<CartChildModel> getChilderns(int groupPos){

        return ListChildTerbaru.get(groupPos);
    }

    @Override
    public CartParentModel getGroup(int groupPosition) {
        return ListTerbaru.get(groupPosition);
    }

    @Override
    public int getGroupCount() {
        return ListTerbaru.size();
    }

    @Override
    public long getGroupId(int groupPosition) {
        return groupPosition;
    }

    @Override
    public View getGroupView(int groupPosition, boolean isExpanded, View convertView, ViewGroup parent) {

        CartParentModel terbaruModel = (CartParentModel) getGroup(groupPosition);
        AddToCartExpandable.ViewHolder holder= null;
        if (convertView == null) {
            LayoutInflater infalInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            convertView = infalInflater.inflate(R.layout.row_item_add_to_cart_parent, null);

            holder=new AddToCartExpandable.ViewHolder();
            holder.parent_tv=(TextView)convertView.findViewById(R.id.parent_tv);

            convertView.setTag(holder);

        }

        else{
            holder=(AddToCartExpandable.ViewHolder)convertView.getTag();
        }

        String checkRequired = terbaruModel.getRequired();
        if (checkRequired.equalsIgnoreCase("1")) {

            holder.parent_tv.setText(terbaruModel.getParentName() + " (Required)");
            FLAG_CHECKBOX = false;
        }
        else {
            holder.parent_tv.setText(terbaruModel.getParentName());
            FLAG_CHECKBOX = true;
        }


        return convertView;
    }

    @Override
    public boolean hasStableIds() {
        return true;
    }

    @Override
    public boolean isChildSelectable(int arg0, int arg1) {
        return true;
    }



    static class ViewHolder{
        TextView parent_tv,radio_btn_item_name,item_price_tv;
        RadioButton radio_btn;
        CheckBox check_btn;
       RadioGroup radio_btn_group;
    }


}

