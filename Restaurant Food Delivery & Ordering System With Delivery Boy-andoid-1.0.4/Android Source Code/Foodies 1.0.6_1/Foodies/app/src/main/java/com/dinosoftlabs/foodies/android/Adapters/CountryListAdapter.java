package com.dinosoftlabs.foodies.android.Adapters;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Filter;
import android.widget.Filterable;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.android.volley.toolbox.ImageLoader;
import com.dinosoftlabs.foodies.android.Models.CountryListModel;
import com.dinosoftlabs.foodies.android.R;
import com.squareup.picasso.Picasso;

import java.util.ArrayList;

/**
 * Created by Nabeel on 1/3/2018.
 */

public class CountryListAdapter extends RecyclerView.Adapter<CountryListAdapter.ViewHolder> implements Filterable {

    ArrayList<CountryListModel> getDataAdapter;
    private ArrayList<CountryListModel> mFilteredList;
    Context context;
    ImageLoader imageLoader1;
    OnItemClickListner onItemClickListner;

    public CountryListAdapter(ArrayList<CountryListModel> getDataAdapter, Context context){
        super();
        this.getDataAdapter = getDataAdapter;
        this.mFilteredList = getDataAdapter;
        this.context = context;

    }

    @Override
    public CountryListAdapter.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_item_country_select, parent, false);

        CountryListAdapter.ViewHolder viewHolder = new CountryListAdapter.ViewHolder(v);

        return viewHolder;
    }

    @Override
    public void onBindViewHolder(CountryListAdapter.ViewHolder holder, final int position) {


        imageLoader1 = ServerImageParseAdapter.getInstance(context).getImageLoader();
        holder.country_name.setText(mFilteredList.get(position).getCountry_name());
        Picasso.with(context).load("http://api.android.pk/app/webroot/uploads/countries/"+mFilteredList.get(position).getCountry_name()+".png").
                fit().centerCrop()
                .placeholder(R.mipmap.ic_launcher)
                .error(R.drawable.unknown_deal).into(holder.country_image);

        holder.main_view.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                onItemClickListner.OnItemClicked(v, position);
            }
        });

    }


    @Override
    public int getItemCount() {
        return mFilteredList.size() ;
    }
    @SuppressWarnings("unchecked")
    @Override
    public Filter getFilter() {

        return new Filter() {
            @Override
            protected FilterResults performFiltering(CharSequence charSequence) {
                String charString = charSequence.toString();
                if (charString.isEmpty()) {
                    mFilteredList = getDataAdapter;
                } else {
                    ArrayList<CountryListModel> filteredList = new ArrayList<>();
                    for (CountryListModel row : getDataAdapter) {

                        // name match condition. this might differ depending on your requirement
                        // here we are looking for name or phone number match
                        if (row.getCountry_name().toLowerCase().contains(charString.toLowerCase()) || row.getCountry_name().contains(charSequence)) {
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
                mFilteredList = (ArrayList<CountryListModel>) filterResults.values;
                notifyDataSetChanged();
            }
        };

    }


    class ViewHolder extends RecyclerView.ViewHolder{

        public TextView country_name;
        public ImageView country_image;
        public RelativeLayout main_view;

        public ViewHolder(View itemView) {

            super(itemView);


            country_image = itemView.findViewById(R.id.flag_image);
            country_name = itemView.findViewById(R.id.country_name_tv);
            main_view = itemView.findViewById(R.id.main_view);


        }
    }

    public interface OnItemClickListner {
        void OnItemClicked(View view, int position);
    }

    public void setOnItemClickListner(OnItemClickListner onCardClickListner) {
        this.onItemClickListner = onCardClickListner;
    }
}