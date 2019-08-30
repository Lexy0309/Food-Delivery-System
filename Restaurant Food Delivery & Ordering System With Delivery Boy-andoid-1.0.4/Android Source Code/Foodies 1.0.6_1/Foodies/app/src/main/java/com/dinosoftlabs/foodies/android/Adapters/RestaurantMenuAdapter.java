package com.dinosoftlabs.foodies.android.Adapters;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseExpandableListAdapter;
import android.widget.Filter;
import android.widget.Filterable;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.RestaurantMenuItems.RestaurantMenuItemsFragment;
import com.dinosoftlabs.foodies.android.Models.RestaurantChildModel;
import com.dinosoftlabs.foodies.android.Models.RestaurantParentModel;
import com.dinosoftlabs.foodies.android.R;


import java.util.ArrayList;

/**
 * Created by Nabeel on 1/10/2018.
 */

public class RestaurantMenuAdapter extends BaseExpandableListAdapter implements Filterable {
    Context context;
    ArrayList<RestaurantParentModel>ListTerbaru;
    ArrayList<ArrayList<RestaurantChildModel>> ListChildTerbaru;
    private   ArrayList<RestaurantParentModel> mFilteredList;
    public static boolean FLAG_OUT_OF_ORDER;


    public RestaurantMenuAdapter (Context context, ArrayList<RestaurantParentModel> ListTerbaru, ArrayList<ArrayList<RestaurantChildModel>> ListChildTerbaru){
        this.context=context;
        this.ListTerbaru=ListTerbaru;
        this.ListChildTerbaru=ListChildTerbaru;
        this.mFilteredList = ListTerbaru;
//      this.count=ListTerbaru.size();
//      this.count=ListChildTerbaru.size();
    }
    @Override
    public boolean areAllItemsEnabled()
    {
        return true;
    }


    @Override
    public RestaurantChildModel getChild(int groupPosition, int childPosition) {
        return ListChildTerbaru.get(groupPosition).get(childPosition);
    }

    @Override
    public long getChildId(int groupPosition, int childPosition) {
        return childPosition;
    }


    @Override
    public View getChildView(int groupPosition, int childPosition, boolean isLastChild, View convertView, ViewGroup parent) {

        RestaurantChildModel childTerbaru = getChild(groupPosition, childPosition);
        RestaurantMenuAdapter.ViewHolder holder= null;

        if (convertView == null) {
            LayoutInflater infalInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            convertView = infalInflater.inflate(R.layout.row_item_restaurant_child, null);

            holder=new RestaurantMenuAdapter.ViewHolder();
            holder.title_name_child=(TextView)convertView.findViewById(R.id.title_name_child);
            holder.sub_title_name_child = convertView.findViewById(R.id.sub_title_name_child);
            holder.price_tv = convertView.findViewById(R.id.price_tv);
            holder.order_status_tv = convertView.findViewById(R.id.order_status_tv);

            convertView.setTag(holder);

        }
        else{
            holder=(RestaurantMenuAdapter.ViewHolder)convertView.getTag();
        }
        String get_order_status = childTerbaru.getOrder_detail();
        String get_symbol = childTerbaru.getCurrency_symbol();
        if (get_order_status.equalsIgnoreCase("1"))
        {
            holder.price_tv.setText("Out of order");
            holder.price_tv.setTextSize(11);
            FLAG_OUT_OF_ORDER = true;
        }
        else {
            holder.price_tv.setText(get_symbol+childTerbaru.getPrice());
            holder.price_tv.setTextSize(14);
            FLAG_OUT_OF_ORDER = false;
        }
                holder.title_name_child.setText(childTerbaru.getChild_title());
                String subtitle = childTerbaru.getChild_sub_title().replaceAll("&amp;", "&");
                holder.sub_title_name_child.setText(subtitle);


        return convertView;
    }
    @Override
    public int getChildrenCount(int groupPosition) {
        return ListChildTerbaru.get(groupPosition).size();
    }@Override
    public RestaurantParentModel getGroup(int groupPosition) {
        return mFilteredList.get(groupPosition);
    }

    @Override
    public int getGroupCount() {
        return mFilteredList.size();
    }

    @Override
    public long getGroupId(int groupPosition) {
        return groupPosition;
    }
    @Override
    public View getGroupView(int groupPosition, boolean isExpanded, View convertView, ViewGroup parent) {

        RestaurantParentModel terbaruModel =  getGroup(groupPosition);
        RestaurantMenuAdapter.ViewHolder holder= null;
        if (convertView == null) {
            LayoutInflater infalInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            convertView = infalInflater.inflate(R.layout.row_item_restaurant_parent, null);

            holder=new RestaurantMenuAdapter.ViewHolder();
            holder.title_name=(TextView)convertView.findViewById(R.id.title_name);
            holder.sub_title_name=(TextView)convertView.findViewById(R.id.sub_title_name);
            holder.mainDiv_Parent = (RelativeLayout)convertView.findViewById(R.id.mainDiv_Parent);
            convertView.setTag(holder);

        }

        else{
            holder=(RestaurantMenuAdapter.ViewHolder)convertView.getTag();
        }

        holder.title_name.setText(terbaruModel.getTitle());
        if(RestaurantMenuItemsFragment.FLAG_SUGGESTION){
            holder.sub_title_name.setVisibility(View.GONE);
            holder.mainDiv_Parent.setBackgroundColor(context.getResources().getColor(R.color.colorWhite));
        }
        else {
            holder.sub_title_name.setText(terbaruModel.getSub_title());
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

    @SuppressWarnings("unchecked")
    @Override
    public Filter getFilter() {

        return new Filter() {
            @Override
            protected FilterResults performFiltering(CharSequence charSequence) {
                String charString = charSequence.toString();
                if (charString.isEmpty()) {
                    mFilteredList = ListTerbaru;
                } else {
                    ArrayList<RestaurantParentModel> filteredList = new ArrayList<>();
                    for (RestaurantParentModel row : ListTerbaru) {

                        // name match condition. this might differ depending on your requirement
                        // here we are looking for name or phone number match
                        if (row.getTitle().toLowerCase().contains(charString.toLowerCase())) {
                            filteredList.add(row);
                        }
                    }

                    mFilteredList = filteredList;
                }

                FilterResults filterResults = new FilterResults();
                filterResults.values = mFilteredList;
                return filterResults;
            }

            @Override
            protected void publishResults(CharSequence charSequence, FilterResults filterResults) {
                mFilteredList = (ArrayList<RestaurantParentModel>) filterResults.values;
                notifyDataSetChanged();
            }
        };


    }


    static class ViewHolder{
        TextView title_name,sub_title_name,title_name_child,sub_title_name_child,price_tv,order_status_tv;
        RelativeLayout mainDiv_Parent;
    }


}