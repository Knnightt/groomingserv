import { Text, TouchableOpacity, View, Image } from 'react-native';

const SOCIAL_ICONS = {
  google: 'https://www.google.com/favicon.ico',
  facebook: 'https://www.facebook.com/favicon.ico',
};

const SocialButton = ({ provider, onPress, style }) => {
  const label = provider.charAt(0).toUpperCase() + provider.slice(1);
  
  return (
    <TouchableOpacity
      onPress={onPress}
      style={[
        {
          flexDirection: 'row',
          alignItems: 'center',
          justifyContent: 'center',
          paddingVertical: 14,
          paddingHorizontal: 24,
          borderRadius: 25,
          borderWidth: 1,
          borderColor: '#E5E5E5',
          backgroundColor: '#FFFFFF',
          flex: 1,
          marginHorizontal: 6,
        },
        style,
      ]}
    >
      <Image
        source={{ uri: SOCIAL_ICONS[provider] }}
        style={{ width: 20, height: 20, marginRight: 10 }}
      />
      <Text style={{ fontSize: 14, color: '#333333', fontWeight: '500' }}>
        {label}
      </Text>
    </TouchableOpacity>
  );
};

export default SocialButton;
