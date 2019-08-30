package com.dinosoftlabs.foodies.android.Adapters;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseExpandableListAdapter;
import android.widget.TextView;

import com.dinosoftlabs.foodies.android.Models.CartFragChildModel;
import com.dinosoftlabs.foodies.android.Models.CartFragParentModel;
import com.dinosoftlabs.foodies.android.R;


import java.util.ArrayList;

/**
 * Created by Nabeel on 2/26/2018.
 */

public class CartFragExpandable  extends BaseExpandableListAdapter {

    Context context;
    ArrayList<CartFragParentModel> ListTerbaru;
    ArrayList<ArrayList<CartFragChildModel>> ListChildTerbaru;

    public CartFragExpandable (Context context, ArrayList<CartFragParentModel>ListTerbaru, ArrayList<ArrayList<CartFragChildModel>> ListChildTerbaru){
        this.context=context;
        this.ListTerbaru=ListTerbaru;
        this.ListChildTerbaru=ListChildTerbaru;

    }
    @Override
    public boolean areAllItemsEnabled()
    {
        return true;
    }


    @Override
    public CartFragChildModel getChild(int groupPosition, int childPosition) {
        return ListChildTerbaru.get(groupPosition).get(childPosition);
    }

    @Override
    public long getChildId(int groupPosition, int childPosition)
    {
        return childPosition;
    }

    @Override
    public View getChildView(int groupPosition, final int childPosition, boolean isLastChild, View convertView, ViewGroup parent) {


        final CartFragChildModel childTerbaru = getChild(groupPosition, childPosition);

        CartFragExpandable.ViewHolder holder= null;
        notifyDataSetChanged();

        if (convertView == null) {
            LayoutInflater infalInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            convertView = infalInflater.inflate(R.layout.row_item_cart_child, null);

            holder=new CartFragExpandable.ViewHolder();
            holder.item_detail_tv = (TextView)convertView.findViewById(R.id.item_detail_tv);

            convertView.setTag(holder);
        }
        else{
            holder=(CartFragExpandable.ViewHolder)convertView.getTag();
        }

        String quantity = childTerbaru.getQuantity();
        String name = childTerbaru.getName();
        String price = childTerbaru.getPrice();
        String symbol = childTerbaru.getSymbol();
        holder.item_detail_tv.setText(quantity+"x "+name+" + "+symbol+price);

        return convertView;
    }
    @Override
    public int getChildrenCount(int groupPosition) {
        if(ListChildTerbaru.get(groupPosition).size() != 0){
            return ListChildTerbaru.get(groupPosition).size();
        }
        return 0;

    }

    @Override
    public CartFragParentModel getGroup(int groupPosition) {
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

        CartFragParentModel terbaruModel = (CartFragParentModel) getGroup(groupPosition);
        CartFragExpandable.ViewHolder holder= null;
        if (convertView == null) {
            LayoutInflater infalInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            convertView = infalInflater.inflate(R.layout.row_item_cart_parent, null);

         /*   ExpandableListView mExpandableListView = (ExpandableListView) parent;
            mExpandableListView.expandGroup(groupPosition);*/

            holder=new CartFragExpandable.ViewHolder();
            holder.name_tv=(TextView)convertView.findViewById(R.id.name_tv);
            holder.price_tv = (TextView)convertView.findViewById(R.id.price_tv);

            convertView.setTag(holder);

        }

        else{
            holder=(CartFragExpandable.ViewHolder)convertView.getTag();
        }
        String quantity = terbaruModel.getItem_quantity();
        String name = terbaruModel.getItem_name();
        String price = terbaruModel.getItem_price();
        String symbol = terbaruModel.getItem_symbol();

        holder.name_tv.setText(name+" x"+quantity);
        holder.price_tv.setText(symbol+price);

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
        TextView name_tv,price_tv,item_detail_tv;
    }


}