package com.dinosoftlabs.foodies.android.Adapters;

import android.content.Context;
import android.os.Parcelable;
import android.support.v4.view.PagerAdapter;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;

import com.android.volley.toolbox.ImageLoader;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Models.ImageSliderModel;

import com.dinosoftlabs.foodies.android.R;
import com.squareup.picasso.Picasso;

import java.util.ArrayList;

/**
 * Created by Nabeel on 12/13/2017.
 */

public class SlidingImageAdapter extends PagerAdapter {

    private ArrayList<ImageSliderModel> IMAGES;
    private LayoutInflater inflater;
    private Context context;
    ImageLoader imageLoader1;


    public SlidingImageAdapter(Context context,ArrayList<ImageSliderModel> IMAGES) {
        this.context = context;
        this.IMAGES=IMAGES;
        inflater = LayoutInflater.from(context);
    }

    @Override
    public void destroyItem(ViewGroup container, int position, Object object) {
        container.removeView((View) object);
    }

    @Override
    public int getCount() {
        return IMAGES.size();
    }

    @Override
    public Object instantiateItem(ViewGroup view, int position) {
        View imageLayout = inflater.inflate(R.layout.slidingimages_layout, view, false);

        assert imageLayout != null;
        final ImageView imageView = (ImageView) imageLayout
                .findViewById(R.id.image_slider);
        ImageSliderModel imageSliderModel = IMAGES.get(position);

        imageLoader1 = ServerImageParseAdapter.getInstance(context).getImageLoader();

        Picasso.with(context).load(Config.imgBaseURL+imageSliderModel.getSliderImageUrl()).
                fit().centerCrop()
                .placeholder(R.drawable.unknown_img)
                .error(R.drawable.unknown_img).into(imageView);


        view.addView(imageLayout, 0);

        return imageLayout;
    }

    @Override
    public boolean isViewFromObject(View view, Object object) {
        return view.equals(object);
    }

    @Override
    public void restoreState(Parcelable state, ClassLoader loader) {
    }

    @Override
    public Parcelable saveState() {
        return null;
    }
}
